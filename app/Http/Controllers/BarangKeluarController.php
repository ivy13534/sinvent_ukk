<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangkeluar;
use App\Models\Barangmasuk;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\Paginator;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        
        $query = DB::table('barangkeluar')
                    ->select('barangkeluar.id', 'barangkeluar.tgl_keluar', 'barangkeluar.qty_keluar','barangkeluar.barang_id', 'barang.merk', 'barang.seri')
                    ->leftJoin('barang', 'barangkeluar.barang_id', '=', 'barang.id');
    
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('barang.id', 'like', '%' . $search . '%')
                  ->orWhere('barang.merk', 'like', '%' . $search . '%')
                  ->orWhere('barangkeluar.tgl_keluar', 'like', '%' . $search . '%')
                  ->orWhere('barangkeluar.qty_keluar', 'like', '%' . $search . '%');
            });
        }
    
        $rsetBarangKeluar = $query->paginate(5);

        Paginator::useBootstrap();
        $barangIds = $rsetBarangKeluar->pluck('barang_id')->toArray();
        $barangData = DB::table('barang')->whereIn('id', $barangIds)->get()->keyBy('id');
    
        return view('v_barangkeluar.index', compact('rsetBarangKeluar', 'barangData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangId = Barang::all();
        return view('v_barangkeluar.create',compact('barangId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $barang = Barang::find($request->barang_id);
    
        if (!$barang) {
            return redirect()->route('barangkeluar.create')
                             ->withErrors(['barang_id' => 'Barang tidak ditemukan.'])
                             ->withInput();
        }
    
        $barangMasuk = Barangmasuk::where('barang_id', $request->barang_id)->first();
    
        if (!$barangMasuk) {
            return redirect()->route('barangkeluar.create')
                             ->withErrors(['barang_id' => 'Data masuk untuk barang ini tidak ditemukan.'])
                             ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'tgl_keluar' => 'required|date|after_or_equal:' . $barangMasuk->tgl_masuk,
            'qty_keluar' => [
                'required',
                'integer',
                'min:1',
                'max:' . Barang::find($request->barang_id)->stok,
            ],
            'barang_id' => 'required|exists:barang,id',
        ], [
            'tgl_keluar.after_or_equal' => 'Tanggal keluar harus setelah atau sama dengan tanggal masuk.',
        ]);
    
        $validator->after(function ($validator) use ($barang, $request) {
            if ($barang->stok < $request->qty_keluar) {
                $validator->errors()->add('qty_keluar', 'Stok tidak mencukupi.');
            }
        });
    
        if ($validator->fails()) {
            return redirect()->route('barangkeluar.create')
                             ->withErrors($validator)
                             ->withInput();
        }
    
        DB::beginTransaction();
        
        try {
            Barangkeluar::create([
                'tgl_keluar' => $request->tgl_keluar,
                'qty_keluar' => $request->qty_keluar,
                'barang_id' => $request->barang_id,
            ]);
    
            $barang->stok -= $request->qty_keluar;
            $barang->save();
    
            DB::commit();
    
            return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (QueryException $e) {
            DB::rollBack();
    
            return redirect()->route('barangkeluar.create')
                             ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])
                             ->withInput();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarangKeluar = Barangkeluar::find($id);

        return view('v_barangkeluar.show', compact('rsetBarangKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $rsetBarangKeluar = Barangkeluar::find($id);
        $barangID = Barang::all();
        return view('v_barangkeluar.edit', compact('rsetBarangKeluar','barangID'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $request->validate( [
        'tgl_keluar' => 'required',
        'qty_keluar' => 'required',
        'barang_id' => 'required',
    ]);

    $rsetBarangKeluar = Barangkeluar::find($id);
    $rsetBarangKeluar->update($request->all());

    return redirect()->route('barangkeluar.index')->with(['Success' => 'Data Berhasil Diubah!']);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rowbarangkeluar = Barangkeluar::find($id);
        $rowbarangkeluar->delete();

        return redirect()->route('barangkeluar.index')->with(['Success' => 'Data Berhasil Dihapus!']);
    }
}
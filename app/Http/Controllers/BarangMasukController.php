<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangmasuk;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $search = $request->search;
    
    $query = DB::table('barangmasuk')
                ->select('barangmasuk.id', 'barangmasuk.tgl_masuk', 'barangmasuk.qty_masuk','barangmasuk.barang_id', 'barang.merk', 'barang.seri')
                ->leftjoin('barang', 'barangmasuk.barang_id', '=', 'barang.id');

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('barang.id', 'like', '%' . $search . '%')
              ->orWhere('barang.merk', 'like', '%' . $search . '%')
              ->orWhere('barangmasuk.tgl_masuk', 'like', '%' . $search . '%')
              ->orWhere('barangmasuk.qty_masuk', 'like', '%' . $search . '%');
        });
    }

    $rsetBarangMasuk = $query->paginate(5);
    Paginator::useBootstrap();

    $barangIds = $rsetBarangMasuk->pluck('barang_id')->toArray();
    $barangData = DB::table('barang')->whereIn('id', $barangIds)->get()->keyBy('id');

    return view('v_barangmasuk.index', compact('rsetBarangMasuk', 'barangData'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangId = Barang::all();
        return view('v_barangmasuk.create',compact('barangId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_masuk' => 'required',
            'qty_masuk' => 'required|integer|min:1',
            'barang_id' => 'required',
        ], [
            'tgl_masuk.required' => 'Tanggal masuk harus diisi.',
            'qty_masuk.required' => 'Qty masuk harus diisi.',
            'qty_masuk.integer' => 'Qty masuk harus berupa angka.',
            'qty_masuk.min' => 'Qty masuk minimal adalah 1.',
            'barang_id.required' => 'Barang harus dipilih.',
        ]);
    
        DB::beginTransaction();
    
        try {
            Barangmasuk::create([
                'tgl_masuk' => $request->tgl_masuk,
                'qty_masuk' => $request->qty_masuk,
                'barang_id' => $request->barang_id,
            ]);
    
            // Commit transaksi jika semua operasi berhasil
            DB::commit();
            return redirect()->route('barangmasuk.index')->with(['success' => 'Data Barang Masuk Berhasil Disimpan!']);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['message' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarangMasuk = BarangMasuk::find($id);

        return view('v_barangmasuk.show', compact('rsetBarangMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rsetBarangMasuk = BarangMasuk::find($id);
        $barangID = Barang::all();
        return view('v_barangmasuk.edit', compact('rsetBarangMasuk', 'barangID'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $request->validate([
        'tgl_masuk' => 'required',
        'qty_masuk' => 'required',
        'barang_id' => 'required',
    ]);

    $rsetBarangMasuk = BarangMasuk::find($id);
    $rsetBarangMasuk->update($request->all());

    return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Diubah!']);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $rsetBarangMasuk = BarangMasuk::find($id);
    
            $stok_barang = $rsetBarangMasuk->barang->stok;
            $qty_masuk = $rsetBarangMasuk->qty_masuk;
            if ($stok_barang < $qty_masuk) {
                throw new \Exception('Stok barang tidak mencukupi untuk menghapus entri barang masuk ini');
            }
    
            $rsetBarangMasuk->delete();
    
            return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Exception $e) {
            return redirect()->route('barangmasuk.index')->with(['error' => $e->getMessage()]);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    use ValidatesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Buat query builder untuk data barang masuk
        $query = DB::table('barangmasuk')
            ->join('barang', 'barangmasuk.barang_id', '=', 'barang.id')
            ->select('barangmasuk.*', 'barang.merk', 'barang.seri', 'barang.spesifikasi');

        // Jika ada parameter pencarian, tambahkan kondisi pencarian
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($query) use ($searchTerm) {
                $query->where('barangmasuk.tgl_masuk', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barangmasuk.qty_masuk', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barang.merk', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barang.seri', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barang.spesifikasi', 'like', '%' . $searchTerm . '%');
            });
        }

        // Ambil data barang masuk menggunakan query builder
        $rsetBarangMasuk = $query->get();

        return view('v_barangmasuk.index', compact('rsetBarangMasuk'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangId = Barang::all();
        return view('v_barangmasuk.create', compact('barangId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validatedData = $this->validate($request, [
        'tgl_masuk' => 'required',
        'qty_masuk' => 'required|numeric|min:0|',
        'barang_id' => 'required',
    ]);

    // Cek apakah jumlah barang masuk akan menyebabkan stok menjadi negatif
    $barang = Barang::findOrFail($validatedData['barang_id']);
    $newStock = $barang->stok + $validatedData['qty_masuk'];
    if ($newStock < 0) {
        return redirect()->back()->withErrors(['qty_masuk' => 'Stok tidak boleh menjadi negatif']);
    }

    // Lanjutkan dengan menyimpan data barang masuk jika stok tidak menjadi negatif
    BarangMasuk::create([
        'tgl_masuk' => $validatedData['tgl_masuk'],
        'qty_masuk' => $validatedData['qty_masuk'],
        'barang_id' => $validatedData['barang_id'],
    ]);

    return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Disimpan!']);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barangMasuk = BarangMasuk::find($id);
        $barangList = Barang::all(); // Ambil semua data barang

        return view('v_barangmasuk.show', compact('barangMasuk', 'barangList'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rsetBarangMasuk = BarangMasuk::find($id);
        $barangId = Barang::all();

        return view('v_barangmasuk.edit', compact('rsetBarangMasuk', 'barangId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $this->validate($request, [
            'tgl_masuk'  => 'required|date',
            'qty_masuk'  => 'required|integer',
            'barang_id'  => 'required|exists:barang,id',
        ]);

        $rsetBarangMasuk = BarangMasuk::find($id);
        $rsetBarangMasuk->update($validatedData);

        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rsetBarangMasuk = BarangMasuk::find($id);
        $rsetBarangMasuk->delete();

        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    use ValidatesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Buat query builder untuk data barang keluar
        $query = DB::table('barangkeluar')
            ->join('barang', 'barangkeluar.barang_id', '=', 'barang.id')
            ->select('barangkeluar.*', 'barang.merk', 'barang.seri', 'barang.spesifikasi');

        // Jika ada parameter pencarian, tambahkan kondisi pencarian
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($query) use ($searchTerm) {
                $query->where('barangkeluar.tgl_keluar', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barangkeluar.qty_keluar', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barang.merk', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barang.seri', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barang.spesifikasi', 'like', '%' . $searchTerm . '%');
            });
        }

        // Ambil data barang keluar menggunakan query builder
        $rsetBarangKeluar = $query->get();

        return view('v_barangkeluar.index', compact('rsetBarangKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangId = Barang::all();
        return view('v_barangkeluar.create', compact('barangId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'tgl_keluar' => 'required|date',
            'qty_keluar' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $barang = Barang::findOrFail($request->barang_id);
                    $stok_barang = $barang->stok;

                    if ($value > $stok_barang) {
                        $fail("Kuantitas tidak boleh melebihi stok ($stok_barang)");
                    } else {
                        // Validasi tanggal keluar tidak boleh sebelum tanggal masuk
                        $tglMasuk = DB::table('barangmasuk')
                            ->where('barang_id', $request->barang_id)
                            ->min('tgl_masuk');

                        if (strtotime($request->tgl_keluar) < strtotime($tglMasuk)) {
                            $fail("Tanggal keluar tidak boleh sebelum tanggal masuk pertama ($tglMasuk)");
                        }
                    }
                },
            ],
            'barang_id' => 'required|exists:barang,id',
        ]);

        // Simpan data barang keluar hanya jika validasi lulus
        BarangKeluar::create([
            'tgl_keluar' => $request->tgl_keluar,
            'qty_keluar' => $request->qty_keluar,
            'barang_id' => $request->barang_id,
        ]);

        // Mengurangi stok barang hanya jika kuantitas valid
        $barang = Barang::findOrFail($request->barang_id);
        $barang->stok -= $request->qty_keluar;
        $barang->save();

        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rsetBarangKeluar = BarangKeluar::find($id);
        $barangId = Barang::all();

        return view('v_barangkeluar.edit', compact('rsetBarangKeluar', 'barangId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $this->validate($request, [
            'tgl_keluar' => 'required|date',
            'qty_keluar' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request, $id) {
                    $barangKeluar = BarangKeluar::findOrFail($id);
                    $barang = Barang::findOrFail($request->barang_id);
                    $stok_barang = $barang->stok + $barangKeluar->qty_keluar;

                    if ($value > $stok_barang) {
                        $fail("Kuantitas tidak boleh melebihi stok ($stok_barang)");
                    } else {
                        // Validasi tanggal keluar tidak boleh sebelum tanggal masuk
                        $tglMasuk = DB::table('barangmasuk')
                            ->where('barang_id', $request->barang_id)
                            ->min('tgl_masuk');

                        if (strtotime($request->tgl_keluar) < strtotime($tglMasuk)) {
                            $fail("Tanggal keluar tidak boleh sebelum tanggal masuk pertama ($tglMasuk)");
                        }
                    }
                },
            ],
            'barang_id' => 'required|exists:barang,id',
        ]);

        // Kembalikan stok sebelumnya
        $barangKeluar = BarangKeluar::findOrFail($id);
        $barang = Barang::findOrFail($request->barang_id);
        $barang->stok += $barangKeluar->qty_keluar;
        $barang->save();

        // Simpan data barang keluar hanya jika kuantitas valid
        $barangKeluar->update([
            'tgl_keluar' => $request->tgl_keluar,
            'qty_keluar' => $request->qty_keluar,
            'barang_id' => $request->barang_id,
        ]);

        // Mengurangi stok barang hanya jika kuantitas valid
        $barang->stok -= $request->qty_keluar;
        $barang->save();

        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarangKeluar = BarangKeluar::find($id);
        return view('v_barangkeluar.show', compact('rsetBarangKeluar'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rsetBarangKeluar = BarangKeluar::find($id);
        $rsetBarangKeluar->delete();

        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}

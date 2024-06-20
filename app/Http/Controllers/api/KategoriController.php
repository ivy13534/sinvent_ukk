<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $rsetKategori = Kategori::all();
        return response()->json($rsetKategori);
    }
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required',
            'kategori' => 'required|in:M,A,BHP,BTHP',
        ]);

        Kategori::create([
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);

        return response()->json(['success' => 'Data Berhasil Disimpan!'], 201);
    }
    public function show($id)
    {
        $kategori = Kategori::find($id);

        if ($kategori) {
            return response()->json($kategori);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'deskripsi' => 'required',
            'kategori' => 'required|in:M,A,BHP,BTHP',
        ]);

        $kategori = Kategori::find($id);

        if ($kategori) {
            $kategori->update([
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
            ]);

            return response()->json(['success' => 'Data Berhasil Diubah!']);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }
    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        if ($kategori) {
            $kategori->delete();
            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

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
            'kategori' => 'required|M,A,BHP,BTHP'
        ]);

        Kategori::create([
            'deskripsi' =>$request->deskripsi,
            'kategori' =>$request->kategori
        ]);

        return response()->json(['success' => 'Data Berhasil Disimpan'], 201);
    }

    public function show(string $id)
    {
        $kategori = Kategori::find($id);
        if ($kategori) {
            return response()->json($kategori);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

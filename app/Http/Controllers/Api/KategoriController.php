<?php

namespace App\Http\Controllers\api;

use App\Models\Kategori;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        $data = array('data'=>$kategori);
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|in:A,M,BHP,BHTP',
            'deskripsi' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori = Kategori::create([
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json($kategori, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = Kategori::find($id);
        if(is_null($kategori)){
            return response()->json(['message'=>'Record not found!'], 404);
        }
        return response()->json($kategori);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|in:A,M,BHP,BTHP',
            'deskripsi' => 'required|max:255',
        ]);

        $kategori = Kategori::find($id);
        if(is_null($kategori)){
            return response()->json(['message'=>'Record not found!'], 404);
        } else {
            $kategori->update($request->all());
            return response()->json($kategori);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::find($id);
        if(is_null($kategori)){
            return response()->json(['message'=>'Record not found!'], 404);
        } else {
            $kategori->delete();
            return response()->json(null, 200);
        }
    }
}
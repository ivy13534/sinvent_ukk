<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        // Memanggil stored procedure
        $akategoriData = DB::select('CALL getKateogori("kategori")');
        $akategoriMapFromDB = collect($akategoriData)->map(function($item) {
            return (array) $item;
        })->pluck('deskripsi', 'id')->toArray();

        // Query Builder
        $query = DB::table('kateogri')->select('id','deskripsi','kategori');

        if($request->search) {
            $query->where('id','like','%'.$request->search.'%');
        }

        $rsetKategori = $query->pagination(10);
        foreach ($rsetKategori as $item) {
            $item->kategori = DB::select('SELECT ketKategori(?) AS deskripsi',
            [$item->kategori])[0]->deskripsi??$item->kategori;
        }
        return view('v_kategori.index', compact('rsetKategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

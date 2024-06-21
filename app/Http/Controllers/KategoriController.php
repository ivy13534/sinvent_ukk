<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProviders;


class KategoriController extends Controller
{
/**
     * Display a listing of the resource.
     */
  
     public function index(Request $request)
     {
         $search = $request->input('search');
     
         $query = DB::table('kategori')
                    ->select('id', 'deskripsi', DB::raw('getKategori(kategori) COLLATE utf8mb4_unicode_ci as kat'));
     
         if ($search) {
             $query->where(function ($q) use ($search) {
                 $q->where('id', 'like', '%' . $search . '%')
                   ->orWhere('deskripsi', 'like', '%' . $search . '%')
                   ->orWhere('kategori', 'like', '%' . $search . '%')
                   ->orWhere(DB::raw('getKategori(kategori) COLLATE utf8mb4_unicode_ci'), 'like', '%' . $search . '%');
             });
         }
     
         $rsetKategori = $query->paginate(5);
         Paginator::useBootstrap();
     
         return view('v_kategori.index', compact('rsetKategori'));
     }
     


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akategori = array('blank'=>'Pilih Kategori',
                            'M'=>'Kategori Modal',
                            'A'=>'Alat',
                            'BHP'=>'Bahan Habis Pakai',
                            'BTHP'=>'Bahan Tidak Habis Pakai'
                            );
        return view('v_kategori.create',compact('akategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required', 
            'kategori' => 'required',
        ], [
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'kategori.required' => 'Kategori harus diisi.',
        ]);
    
        DB::beginTransaction();
    
        try {
            Kategori::create([
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
            ]);
    
            DB::commit();
    
            return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
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
        $rsetKategori = DB::table('kategori')
            ->select('id', 'deskripsi', DB::raw('getKategori(kategori) as kat'))
            ->where('id', $id)
            ->first();
    
        return view('v_kategori.show', compact('rsetKategori'));
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $akategori = array('blank'=>'Pilih Kategori',
                            'M'=>'Modal',
                            'A'=>'Alat',
                            'BHP'=>'Bahan Habis Pakai',
                            'BTHP'=>'Bahan Tidak Habis Pakai'
                            );

        $rsetKategori = Kategori::find($id);
        return view('v_kategori.edit', compact('rsetKategori','akategori'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $request->validate([
        'deskripsi' => 'required',
        'kategori' => 'required',
    ]);

    $rsetKategori = Kategori::find($id);
    $rsetKategori->update($request->all());

    return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Diubah!']);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (DB::table('barang')->where('kategori_id', $id)->exists()){ 
            return redirect()->route('kategori.index')->with(['Gagal' => 'Gagal dihapus']);
        } else {
            $rseKategori = Kategori::find($id);
            $rseKategori->delete();
            return redirect()->route('kategori.index')->with(['Success' => 'Berhasil dihapus']);
        }
    }

}
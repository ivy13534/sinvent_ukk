<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;

class DashboardController extends Controller
{
    public function index() {
        $barang = Barang::all()->count();
        $kategori = Kategori::all()->count();
        $barangmasuk = BarangMasuk::all()->count();
        $barangkeluar = BarangKeluar::all()->count();

        return view('dashboard', compact('barang', 'kategori', 'barangmasuk', 'barangkeluar'));
    }
}
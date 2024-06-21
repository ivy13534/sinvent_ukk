@extends('layouts.adm-main')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8">
               <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Tanggal Keluar</td>
                                <td>{{ $rsetBarangKeluar->tgl_keluar }}</td>
                            </tr>
                            <tr>
                                <td>Quantity</td>
                                <td>{{ $rsetBarangKeluar->qty_keluar }}</td>
                            </tr>
                            <tr>
                                <td>ID Barang - Merk Barang</td>
                                <td>{{ $rsetBarangKeluar->barang_id }} - {{ $rsetBarangKeluar->barang->merk }} {{ $rsetBarangKeluar->barang->seri }}</td>
                            </tr>
                        </table>
                    </div>
               </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12  text-center">
                <br>

                <a href="{{ route('barangkeluar.index') }}" class="btn btn-md btn-primary mt-3">Back</a>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <a href="{{ route('barangmasuk.create') }}" class="btn btn-md btn-success btn-sm pull-right">TAMBAH BARANG MASUK</a>
                                </div>
                                <div class="col-md-6 text-right">
                                <form action="/barangmasuk" method="GET" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(session('Success'))
                    <div class="alert alert-success">
                        {{ session('Success') }}
                    </div>
                @endif

                @if(session('Gagal'))
                    <div class="alert alert-danger">
                        {{ session('Gagal') }}
                    </div>
                @endif
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tgl Masuk</th>
                            <th>Quantity</th>
                            <th>ID - Merk, Seri Barang</th>
                            <th style="width: 15%">AKSI</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rsetBarangMasuk as $index => $rowbarangmasuk)
                            <tr>
                                <td>{{ $loop->iteration + ($rsetBarangMasuk->currentPage() - 1) * $rsetBarangMasuk->perPage() }}</td>
                                <td>{{ $rowbarangmasuk->tgl_masuk  }}</td>
                                <td>{{ $rowbarangmasuk->qty_masuk  }}</td>
                                <td>{{ $rowbarangmasuk->barang_id }} - {{ $rowbarangmasuk->merk }} {{ $rowbarangmasuk->seri }}</td>
                                <td class="text-center"> 
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('barangmasuk.destroy', $rowbarangmasuk->id) }}" method="POST">
                                        <a href="{{ route('barangmasuk.show', $rowbarangmasuk->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('barangmasuk.edit', $rowbarangmasuk->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                                
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Data Barang Masuk belum tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                </table>
                <!-- resources/views/layouts/app.blade.php atau di tempat lain yang sesuai -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <div class="d-flex justify-content-end">
        {{ $rsetBarangMasuk->links() }} 
    </div>
            </div>
        </div>
    </div>
@endsection
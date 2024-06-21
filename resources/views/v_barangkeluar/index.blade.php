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
                                    <a href="{{ route('barangkeluar.create') }}" class="btn btn-md btn-success btn-sm pull-right">TAMBAH BARANG KELUAR</a>
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
                            <th>Tgl Keluar</th>
                            <th>Quantity</th>
                            <th>ID - Merk Barang</th>
                            <th style="width: 15%">AKSI</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rsetBarangKeluar as $index => $rowbarangkeluar)
                            <tr>
                                <td>{{ $loop->iteration + ($rsetBarangKeluar->currentPage() - 1) * $rsetBarangKeluar->perPage() }}</td>
                                <td>{{ $rowbarangkeluar->tgl_keluar  }}</td>
                                <td>{{ $rowbarangkeluar->qty_keluar  }}</td>
                                <td>{{ $rowbarangkeluar->barang_id  }} - {{ $rowbarangkeluar->merk }} {{ $rowbarangkeluar->seri }}</td>
                                <td class="text-center"> 
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('barangkeluar.destroy', $rowbarangkeluar->id) }}" method="POST">
                                        <a href="{{ route('barangkeluar.show', $rowbarangkeluar->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('barangkeluar.edit', $rowbarangkeluar->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                                
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Data Barang Keluar belum tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                </table>
                <div class="d-flex justify-content-end">
                    {{ $rsetBarangKeluar->links() }} 
                </div>

            </div>
        </div>
    </div>
@endsection
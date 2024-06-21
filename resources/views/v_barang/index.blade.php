@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('barang.create') }}" class="btn btn-md btn-success">TAMBAH BARANG</a>
                        <form action="/barang" method="GET" class="d-flex">
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
                            <th>ID</th>
                            <th>MERK</th>
                            <th>SERI</th>
                            <th>SPESIFIKASI</th>
                            <th>STOK</th>
                            <th>KATEGORI</th>
                            <th style="width: 15%">AKSI</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rsetBarang as $rowbarang)
                            <tr>
                                <td>{{ $rowbarang->id  }}</td>
                                <td>{{ $rowbarang->merk  }}</td>
                                <td>{{ $rowbarang->seri  }}</td>
                                <td>{{ $rowbarang->spesifikasi  }}</td>
                                <td>{{ $rowbarang->stok  }}</td>
                                <td>{{ $rowbarang->kategori_id }} - {{ $rowbarang->deskripsi }}</td>
                                <td class="text-center"> 
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('barang.destroy', $rowbarang->id) }}" method="POST">
                                        <a href="{{ route('barang.show', $rowbarang->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('barang.edit', $rowbarang->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                                
                            </tr>
                        @empty
                            <div class="alert">
                                Data belum tersedia
                            </div>
                        @endforelse
                    </tbody>
                    
                </table>
                {{-- {{ $siswa->links() }} --}}

            </div>
        </div>
    </div>
@endsection
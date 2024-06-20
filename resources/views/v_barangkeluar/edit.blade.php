@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('barangkeluar.update', $rsetBarangKeluar->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Keluar</label>
                                <input type="text" class="form-control @error('tgl_keluar') is-invalid @enderror" name="tgl_keluar" value="{{ old('tgl_keluar', $rsetBarangKeluar->tgl_keluar) }}" placeholder="Masukkan Tanggal Keluar">
                                <!-- error message untuk tgl_keluar -->
                                @error('tgl_keluar')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Qty Keluar</label>
                                <input type="number" class="form-control @error('qty_keluar') is-invalid @enderror" name="qty_keluar" value="{{ old('qty_keluar', $rsetBarangKeluar->qty_keluar) }}" placeholder="Masukkan Qty Keluar">
                                <!-- error message untuk qty_keluar -->
                                @error('qty_keluar')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Barang</label>
                                <select class="form-control @error('barang_id') is-invalid @enderror" name="barang_id">
                                    @foreach($barangId as $barang)
                                        <option value="{{ $barang->id }}" {{ old('barang_id', $rsetBarangKeluar->barang_id) == $barang->id ? 'selected' : '' }}>
                                            {{ $barang->merk }} - {{ $barang->seri }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- error message untuk barang_id -->
                                @error('barang_id')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    <form action="{{ route('barangkeluar.store') }}" method="POST" enctype="multipart/form-data">                    
                            @csrf

                            <div class="form-group">
                                <label class="font-weight-bold">TGL_KELUAR</label>
                                <input required type="date" class="form-control @error('tgl_masuk') is-invalid @enderror" name="tgl_keluar" value="{{ old('tgl_masuk') }}" placeholder="Masukkan tgl_masuk">
                            
                                <!-- error message untuk nama -->
                                @error('nama')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
    <label class="font-weight-bold">QTY</label>
    <input type="text" class="form-control @error('qty_keluar') is-invalid @enderror" name="qty_keluar" value="{{ old('qty_keluar') }}" placeholder="Masukkan Jumlah Barang">
    
    <!-- error message untuk qty_keluar -->
    @error('qty_keluar')
        <div class="alert alert-danger mt-2">
            {{ $message }}
        </div>
    @enderror
</div>


                            <div class="form-group">
                                <label class="font-weight-bold">BARANG_ID</label>
                                <select name="barang_id" id="">
                                    @foreach($barangId as $barangIdrow)
                                        <option value="{{$barangIdrow->id}}">{{$barangIdrow->id}}</option>
                                    @endforeach
                                </select>
                            
                                <!-- error message untuk nis -->
                                @error('nis')
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
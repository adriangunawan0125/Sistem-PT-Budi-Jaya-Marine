@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Tambah Ex Mitra</h4>

    <div class="card shadow">
        <div class="card-body">

            <form action="{{ route('ex-mitra.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Jaminan</label>
                    <input type="text" name="jaminan" class="form-control" value="{{ old('jaminan') }}">
                </div>

                <div class="form-group mb-3">
                    <label>No Handphone</label>
                    <input type="text" name="no_handphone" class="form-control" value="{{ old('no_handphone') }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                </div>

                <div class="text-end">
                    <a href="{{ route('ex-mitra.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

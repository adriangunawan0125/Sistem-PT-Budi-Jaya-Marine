@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Edit Ex Mitra</h4>

    <div class="card shadow">
        <div class="card-body">

            <form action="{{ route('ex-mitra.update', $exMitra->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control"
                        value="{{ old('nama', $exMitra->nama) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Jaminan</label>
                    <input type="text" name="jaminan" class="form-control"
                        value="{{ old('jaminan', $exMitra->jaminan) }}">
                </div>

                <div class="form-group mb-3">
                    <label>No Handphone</label>
                    <input type="text" name="no_handphone" class="form-control"
                        value="{{ old('no_handphone', $exMitra->no_handphone) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $exMitra->keterangan) }}</textarea>
                </div>

                <div class="text-end">
                    <a href="{{ route('ex-mitra.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-warning">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Pemasukan Transport</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemasukan.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ old('tanggal') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ old('deskripsi') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nominal</label>
            <input type="number"
                   name="nominal"
                   class="form-control"
                   value="{{ old('nominal') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar (Bukti)</label>
            <input type="file"
                   name="gambar"
                   class="form-control"
                   accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">
            Simpan
        </button>

        <a href="{{ route('pemasukan.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>
@endsection

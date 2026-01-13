@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Tambah Mitra</h4>

    <form action="/admin-transport/mitra/store" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Mitra</label>
            <input type="text" name="nama_mitra" class="form-control"
                   value="{{ old('nama_mitra') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Unit / No Polisi</label>
            <select name="unit_id" class="form-control" required>
                <option value="">-- Pilih Unit --</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}">
    {{ $unit->nama_unit }} - {{ $unit->merek }}
</option>


                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" name="no_hp" class="form-control"
                   value="{{ old('no_hp') }}" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="/admin-transport/mitra" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

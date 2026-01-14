@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Mitra</h4>

    <form action="/admin-transport/mitra/update/{{ $mitra->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Mitra</label>
            <input type="text"
                   name="nama_mitra"
                   class="form-control"
                   value="{{ old('nama_mitra', $mitra->nama_mitra) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Unit / No Polisi</label>
            <select name="unit_id" class="form-control" required>
                <option value="">-- Pilih Unit --</option>

                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}"
                        {{ $mitra->unit_id == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nama_unit }} - {{ $unit->merek }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat"
                      class="form-control"
                      rows="3"
                      required>{{ old('alamat', $mitra->alamat) }}</textarea>
        </div>

        {{-- 🔥 INI YANG TADI HILANG --}}
        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text"
                   name="no_hp"
                   class="form-control"
                   value="{{ old('no_hp', $mitra->no_hp) }}"
                   required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="/admin-transport/mitra" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

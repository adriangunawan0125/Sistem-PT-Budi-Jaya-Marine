@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Mitra</h4>

    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ url('/admin-transport/mitra') }}" method="POST">
        @csrf

        {{-- NAMA MITRA --}}
        <div class="mb-3">
            <label>Nama Mitra</label>
            <input type="text"
                   name="nama_mitra"
                   class="form-control @error('nama_mitra') is-invalid @enderror"
                   value="{{ old('nama_mitra') }}"
                   required>

            @error('nama_mitra')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- UNIT --}}
        <div class="mb-3">
            <label>Unit</label>
            <select name="unit_id"
                    class="form-control @error('unit_id') is-invalid @enderror"
                    required>
                <option value="">-- Pilih Unit --</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}"
                        {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nama_unit }}
                    </option>
                @endforeach
            </select>

            @error('unit_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- ALAMAT --}}
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat"
                      class="form-control @error('alamat') is-invalid @enderror"
                      rows="3"
                      required>{{ old('alamat') }}</textarea>

            @error('alamat')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- NO HP --}}
        <div class="mb-3">
            <label>No HP</label>
            <input type="text"
                   name="no_hp"
                   class="form-control @error('no_hp') is-invalid @enderror"
                   value="{{ old('no_hp') }}"
                   required>

            @error('no_hp')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- KONTRAK --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Kontrak Mulai</label>
                <input type="date"
                       name="kontrak_mulai"
                       class="form-control @error('kontrak_mulai') is-invalid @enderror"
                       value="{{ old('kontrak_mulai') }}">

                @error('kontrak_mulai')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label>Kontrak Berakhir</label>
                <input type="date"
                       name="kontrak_berakhir"
                       class="form-control @error('kontrak_berakhir') is-invalid @enderror"
                       value="{{ old('kontrak_berakhir') }}">

                @error('kontrak_berakhir')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- BUTTON --}}
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ url('/admin-transport/mitra') }}" class="btn btn-secondary">
            Kembali
        </a>

    </form>
</div>
@endsection

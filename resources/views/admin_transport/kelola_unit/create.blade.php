@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Unit</h4>

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

    <form action="/admin-transport/unit/store" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Unit</label>
            <input type="text" 
                   name="nama_unit" 
                   class="form-control @error('nama_unit') is-invalid @enderror"
                   value="{{ old('nama_unit') }}"
                   required>

            @error('nama_unit')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label>Merek</label>
            <input type="text" 
                   name="merek" 
                   class="form-control @error('merek') is-invalid @enderror"
                   value="{{ old('merek') }}"
                   required>

            @error('merek')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- STATUS --}}
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                <option value="tersedia" selected>Tersedia</option>
            </select>
        </div>

        {{-- STNK --}}
        <div class="mb-3">
            <label>Masa Berlaku STNK</label>
            <input type="date"
                   name="stnk_expired_at"
                   class="form-control @error('stnk_expired_at') is-invalid @enderror"
                   value="{{ old('stnk_expired_at') }}">

            @error('stnk_expired_at')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <small class="text-muted">Kosongkan jika belum diisi</small>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="/admin-transport/unit" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

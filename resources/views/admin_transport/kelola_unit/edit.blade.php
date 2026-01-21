@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Unit</h4>

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

    <form action="/admin-transport/unit/update/{{ $unit->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Unit</label>
            <input type="text"
                   name="nama_unit"
                   class="form-control @error('nama_unit') is-invalid @enderror"
                   value="{{ old('nama_unit', $unit->nama_unit) }}"
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
                   value="{{ old('merek', $unit->merek) }}"
                   required>

            @error('merek')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="/admin-transport/unit" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

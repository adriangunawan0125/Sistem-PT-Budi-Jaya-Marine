@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Edit Pemasukan Transport</h4>

    {{-- Alert error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemasukan.update', $pemasukan->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Tanggal --}}
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ old('tanggal', $pemasukan->tanggal) }}"
                   required>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ old('deskripsi', $pemasukan->deskripsi) }}"
                   required>
        </div>

        {{-- Nominal --}}
        <div class="mb-3">
            <label class="form-label">Nominal</label>
            <input type="number"
                   name="nominal"
                   class="form-control"
                   value="{{ old('nominal', $pemasukan->nominal) }}"
                   required>
        </div>

        {{-- Gambar --}}
        <div class="mb-3">
            <label class="form-label">Gambar (Opsional)</label>
            <input type="file"
                   name="gambar"
                   class="form-control">

            {{-- Gambar saat ini --}}
            @if ($pemasukan->gambar)
                <div class="mt-2">
                    <p class="mb-1 text-muted">Gambar saat ini:</p>
                    <img src="{{ asset('storage/pemasukan/'.$pemasukan->gambar) }}"
                         width="150"
                         class="img-thumbnail">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">
            Update
        </button>

        <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">
            Batal
        </a>
    </form>
</div>
@endsection

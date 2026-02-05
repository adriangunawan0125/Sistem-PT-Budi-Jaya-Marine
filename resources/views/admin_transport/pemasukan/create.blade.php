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
    <label class="form-label">Nama Mitra</label>
    <select name="mitra_id" class="form-control" required>
        <option value="">-- Pilih Mitra --</option>
        @foreach($mitras as $mitra)
            <option value="{{ $mitra->id }}"
                {{ old('mitra_id') == $mitra->id ? 'selected' : '' }}>
                {{ $mitra->nama_mitra }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Kategori</label>
    <select name="kategori" class="form-control" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="setoran">Setoran</option>
        <option value="cicilan">Cicilan</option>
        <option value="deposit">Deposit</option>
    </select>
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

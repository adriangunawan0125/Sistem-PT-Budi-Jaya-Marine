@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Edit Pemasukan Transport</h4>

    {{-- ERROR --}}
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

        {{-- MITRA (READ ONLY) --}}
        <div class="mb-3">
            <label class="form-label">Nama Mitra</label>
            <input type="text"
                   class="form-control"
                   value="{{ $pemasukan->mitra->nama_mitra ?? '-' }}"
                   disabled>
        </div>

        {{-- TANGGAL --}}
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ old('tanggal', \Carbon\Carbon::parse($pemasukan->tanggal)->format('Y-m-d')) }}"
                   required>
        </div>

        {{-- KATEGORI --}}
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="setoran" {{ old('kategori', $pemasukan->kategori)=='setoran'?'selected':'' }}>Setoran</option>
                <option value="cicilan" {{ old('kategori', $pemasukan->kategori)=='cicilan'?'selected':'' }}>Cicilan</option>
                <option value="deposit" {{ old('kategori', $pemasukan->kategori)=='deposit'?'selected':'' }}>Deposit</option>
            </select>
        </div>

        {{-- DESKRIPSI --}}
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ old('deskripsi', $pemasukan->deskripsi) }}"
                   required>
        </div>

        {{-- NOMINAL --}}
        <div class="mb-3">
            <label class="form-label">Nominal</label>
            <input type="number"
                   name="nominal"
                   class="form-control"
                   min="0"
                   step="1"
                   value="{{ old('nominal', $pemasukan->nominal) }}"
                   required>
        </div>

        {{-- GAMBAR --}}
        <div class="mb-3">
            <label class="form-label">Ganti Gambar (Opsional)</label>
            <input type="file"
                   name="gambar"
                   class="form-control"
                   accept="image/*"
                   onchange="previewImage(event)">

            {{-- Preview gambar baru --}}
            <img id="preview" class="img-thumbnail mt-2" width="150" style="display:none;">

            {{-- Gambar lama --}}
            @if ($pemasukan->gambar)
                <div class="mt-3">
                    <p class="mb-1 text-muted">Gambar saat ini:</p>
                    <img src="{{ asset('storage/pemasukan/'.$pemasukan->gambar) }}"
                         width="150"
                         class="img-thumbnail">
                </div>
            @endif
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                Update
            </button>

            <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

{{-- Preview Script --}}
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = 'block';
}
</script>

@endsection

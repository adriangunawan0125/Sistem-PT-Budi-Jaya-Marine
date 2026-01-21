@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tambah Pengeluaran Internal</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pengeluaran_internal.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control" placeholder="Contoh: Beli ATK" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nominal</label>
                    <input type="number" name="nominal" class="form-control" placeholder="Contoh: 50000" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar (opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <small class="text-muted">Format JPG, PNG, maksimal 2MB</small>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Simpan
                </button>
                <a href="{{ route('pengeluaran_internal.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

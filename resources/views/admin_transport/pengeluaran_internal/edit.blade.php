@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0">Edit Pengeluaran Internal</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pengeluaran_internal.update', $pengeluaranInternal->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $pengeluaranInternal->tanggal }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control" value="{{ $pengeluaranInternal->deskripsi }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nominal</label>
                    <input type="number" name="nominal" class="form-control" value="{{ $pengeluaranInternal->nominal }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar (opsional)</label>
                    @if($pengeluaranInternal->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$pengeluaranInternal->gambar) }}" alt="Gambar Lama" width="100" class="img-thumbnail">
                        </div>
                    @endif
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <small class="text-muted">Format JPG, PNG, maksimal 2MB. Upload untuk mengganti gambar lama.</small>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Update
                </button>
                <a href="{{ route('pengeluaran_internal.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

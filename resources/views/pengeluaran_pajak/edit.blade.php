@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Pengeluaran Pajak</h4>

    <form method="POST" action="{{ route('pengeluaran_pajak.update', $pengeluaranPajak->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Plat Nomor</label>
            <select name="unit_id" class="form-control" required>
                <option value="">Pilih Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ $pengeluaranPajak->unit_id == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nama_unit }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $pengeluaranPajak->tanggal }}" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <input type="text" name="deskripsi" class="form-control" value="{{ $pengeluaranPajak->deskripsi }}" required>
        </div>

        <div class="mb-3">
            <label>Nominal</label>
            <input type="number" name="nominal" class="form-control" value="{{ $pengeluaranPajak->nominal }}" required>
        </div>

        <div class="mb-3">
            <label>Gambar</label>
            @if($pengeluaranPajak->gambar)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$pengeluaranPajak->gambar) }}" alt="Gambar" width="120" class="img-thumbnail">
                </div>
            @endif
            <input type="file" name="gambar" class="form-control" accept="image/*">
            <small class="text-muted">Opsional, pilih jika ingin mengganti gambar</small>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

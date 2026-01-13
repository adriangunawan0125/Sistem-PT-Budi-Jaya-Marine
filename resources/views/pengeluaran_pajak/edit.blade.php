@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Pengeluaran Pajak</h4>

    <form method="POST" action="{{ route('pengeluaran_pajak.store') }}">
        @csrf
        <div class="mb-3">
            <label>Plat Nomor</label>
           <select name="unit_id" class="form-control" required>
    @foreach($units as $unit)
        <option value="{{ $unit->id }}" {{ $pengeluaranPajak->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
    @endforeach
</select>

        </div>
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <input type="text" name="deskripsi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nominal</label>
            <input type="number" name="nominal" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection

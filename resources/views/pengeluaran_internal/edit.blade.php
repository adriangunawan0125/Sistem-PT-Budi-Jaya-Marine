@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Pengeluaran Internal</h4>

    <form method="POST" action="{{ route('pengeluaran_internal.update', $pengeluaranInternal->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $pengeluaranInternal->tanggal }}" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <input type="text" name="deskripsi" class="form-control" value="{{ $pengeluaranInternal->deskripsi }}" required>
        </div>
        <div class="mb-3">
            <label>Nominal</label>
            <input type="number" name="nominal" class="form-control" value="{{ $pengeluaranInternal->nominal }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

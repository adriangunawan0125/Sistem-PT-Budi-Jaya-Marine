@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Mitra</h4>

    <form action="/admin-transport/mitra/update/{{ $mitra->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Mitra</label>
            <input type="text"
                   name="nama_mitra"
                   class="form-control"
                   value="{{ $mitra->nama_mitra }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Unit / No Polisi</label>
            <select name="unit_id" class="form-control" required>
    @foreach ($units as $unit)
        <option value="{{ $unit->id }}"
            {{ $unit->id == $mitra->unit_id ? 'selected' : '' }}>
            {{ $unit->nama_unit }} ({{ $unit->merek }})
        </option>
    @endforeach
</select>


        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat"
                      class="form-control"
                      rows="3"
                      required>{{ $mitra->alamat }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Update
        </button>
    </form>
</div>
@endsection

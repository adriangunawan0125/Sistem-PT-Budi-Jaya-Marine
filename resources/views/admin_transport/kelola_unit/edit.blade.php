@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Unit</h4>

    <form action="/admin-transport/unit/update/{{ $unit->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Unit</label>
            <input type="text"
                   name="nama_unit"
                   class="form-control"
                   value="{{ $unit->nama_unit }}" required>
        </div>
        
         <div class="form-group">
            <label>Merek</label>
            <input type="text" name="merek" class="form-control" {{ $unit->merek }}required>
        </div>



        <button class="btn btn-primary">Update</button>
        <a href="/admin-transport/unit" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

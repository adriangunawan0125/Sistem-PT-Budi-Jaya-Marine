@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Unit</h4>

    <form action="/admin-transport/unit/store" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Unit</label>
            <input type="text" name="nama_unit" class="form-control" required>
        </div>
        
        <div class="form-group">
   
            
            <label>Merek</label>
            <input type="text" name="merek" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="tersedia">Tersedia</option>
               
            </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="/admin-transport/unit" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Tambah Perusahaan</h3>

    <form method="POST" action="{{ route('companies.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Perusahaan</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="address"
                      class="form-control"
                      rows="3">{{ old('address') }}</textarea>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('companies.index') }}"
           class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Edit Perusahaan</h3>

    <form method="POST"
          action="{{ route('companies.update', $company->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Perusahaan</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $company->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="address"
                      class="form-control"
                      rows="3">{{ old('address', $company->address) }}</textarea>
        </div>

        <button class="btn btn-warning">Update</button>
        <a href="{{ route('companies.index') }}"
           class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

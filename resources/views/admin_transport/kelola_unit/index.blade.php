@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Daftar Unit</h4>

    {{-- Alert sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tombol tambah --}}
    <a href="/admin-transport/unit/create" class="btn btn-primary mb-3">
        Tambah Unit
    </a>

    {{-- 🔍 SEARCH & FILTER --}}
    <form method="GET" action="" class="row mb-3">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama unit..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">-- Semua Status --</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>
                    Tersedia
                </option>
                <option value="disewakan" {{ request('status') == 'disewakan' ? 'selected' : '' }}>
                    Disewakan
                </option>
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">Filter</button>
            <a href="/admin-transport/unit" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>

    {{-- 📋 TABEL --}}
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Nama Unit</th>
                <th>Merek Mobil</th>
                <th>Status</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($units as $no => $unit)
                <tr>
                    {{-- nomor auto lanjut walau pagination --}}
                    <td>{{ $units->firstItem() + $no }}</td>

                    <td>{{ $unit->nama_unit }}</td>
                    <td>{{ $unit->merek }}</td>

                    <td>
                        <span class="badge text-white px-3 py-2
                            {{ $unit->status == 'disewakan' ? 'bg-danger' : 'bg-success' }}">
                            {{ ucfirst($unit->status) }}
                        </span>
                    </td>

                    <td>
                        <a href="/admin-transport/unit/edit/{{ $unit->id }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="/admin-transport/unit/delete/{{ $unit->id }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus data?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Data unit tidak ditemukan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 📄 PAGINATION --}}
    <div class="d-flex justify-content-center">
        {{ $units->links() }}
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Daftar Mitra</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="/admin-transport/mitra/create" class="btn btn-primary mb-3">
        Tambah Mitra
    </a>
    <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Cari nama mitra..."
               value="{{ request('search') }}">
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">
            Cari
        </button>
    </div>

    <div class="col-md-2">
        <a href="/admin-transport/mitra" class="btn btn-secondary w-100">
            Reset
        </a>
    </div>
</form>


    <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th style="width: 50px">No</th>
                <th style="min-width: 150px">Nama Mitra</th>
                <th style="min-width: 150px">No Polisi</th>
                <th style="min-width: 300px">Alamat</th>
                <th style="min-width: 150px">No HP</th>
                <th style="min-width: 120px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mitras as $no => $mitra)
            <tr>
                <td>{{ $mitras->firstItem() + $no }}</td>
                <td>{{ $mitra->nama_mitra }}</td>
                <td>{{ $mitra->unit->nama_unit ?? '-' }}</td>
                <td>{{ $mitra->alamat }}</td>
                <td>{{ $mitra->no_hp }}</td>
                <td>
                    <a href="/admin-transport/mitra/edit/{{ $mitra->id }}"
                       class="btn btn-warning btn-sm mb-1">
                        Edit
                    </a>

                    <form action="/admin-transport/mitra/delete/{{ $mitra->id }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus mitra?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">
                    Data mitra tidak ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

    {{-- Pagination Bootstrap --}}
    <div class="d-flex justify-content-center">
        {{ $mitras->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

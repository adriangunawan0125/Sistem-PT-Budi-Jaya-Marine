@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Data Jaminan Mitra</h4>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    {{-- TOMBOL TAMBAH --}}
    <a href="{{ route('jaminan_mitra.create') }}"
       class="btn btn-primary mb-3">
        Tambah Jaminan
    </a>

    {{-- SEARCH --}}
    <form method="GET" class="row mb-3">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama mitra / no hp / jaminan..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-5">
            <button class="btn btn-primary">Cari</button>
            <a href="{{ route('jaminan_mitra.index') }}"
               class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>

    {{-- TABEL --}}
    <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th width="60">No</th>
                <th>Nama Mitra</th>
                <th>No HP</th>
                <th>Jaminan</th>
                <th width="220">Gambar</th>
                <th width="200" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($data as $no => $item)
            <tr>
                <td>{{ $data->firstItem() + $no }}</td>

                <td class="fw-semibold">
                    {{ $item->mitra->nama_mitra }}
                </td>

                <td>{{ $item->mitra->no_hp }}</td>

                <td>{{ $item->jaminan }}</td>

                {{-- GAMBAR --}}
                <td>
                    @foreach(['gambar_1','gambar_2','gambar_3'] as $g)
                        @if($item->$g)
                            <a href="{{ asset('storage/'.$item->$g) }}"
                               target="_blank">
                                <img src="{{ asset('storage/'.$item->$g) }}"
                                     width="50"
                                     class="me-1 mb-1 rounded border">
                            </a>
                        @endif
                    @endforeach
                </td>

                {{-- AKSI --}}
                <td class="text-center">

                    <a href="{{ route('jaminan_mitra.edit', $item->id) }}"
                       class="btn btn-warning btn-sm me-1 mb-1">
                        Edit
                    </a>

                    <form action="{{ route('jaminan_mitra.destroy', $item->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Yakin hapus data jaminan ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm mb-1">
                            Hapus
                        </button>
                    </form>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    Data jaminan tidak ditemukan
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center">
        {{ $data->links() }}
    </div>

</div>
@endsection

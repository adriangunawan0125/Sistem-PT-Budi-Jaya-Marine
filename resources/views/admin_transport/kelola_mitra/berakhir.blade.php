@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Mitra Kontrak Berakhir</h4>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    {{-- TOMBOL KEMBALI --}}
    <a href="{{ url('/admin-transport/mitra') }}"
       class="btn btn-secondary mb-3">
        ← Kembali ke Mitra
    </a>

    {{-- SEARCH --}}
    <form method="GET" class="row mb-3">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama mitra..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-4">
            <button class="btn btn-primary">Cari</button>
            <a href="{{ url('/admin-transport/mitra/berakhir') }}"
               class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>

    {{-- TABEL --}}
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Nama Mitra</th>
                <th>No HP</th>
                <th>Kontrak</th>
                <th>Status</th>
                <th width="160">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($mitras as $no => $mitra)
            <tr>
                <td>{{ $mitras->firstItem() + $no }}</td>
                <td class="fw-semibold">{{ $mitra->nama_mitra }}</td>
                <td>{{ $mitra->no_hp }}</td>

                {{-- KONTRAK --}}
                <td>
                    <small class="text-muted">Mulai</small> :
                    {{ $mitra->kontrak_mulai?->format('d-m-Y') ?? '-' }}<br>
                    <small class="text-muted">Selesai</small> :
                    {{ $mitra->kontrak_berakhir?->format('d-m-Y') ?? '-' }}
                </td>

                {{-- STATUS --}}
                <td>
                    <span class="badge bg-danger text-white px-3 py-2 d-inline-block text-center"
                          style="min-width:110px">
                        Berakhir
                    </span>
                </td>

                {{-- AKSI --}}
                <td>
                    <form action="{{ url('/admin-transport/mitra/'.$mitra->id.'/aktifkan') }}"
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success btn-sm"
                                onclick="return confirm('Aktifkan kembali mitra ini?')">
                            Aktifkan
                        </button>
                    </form>
                     <form action="{{ url('/admin-transport/mitra/'.$mitra->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Yakin hapus mitra ini? Data tidak bisa dikembalikan!')">
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
                    Data mitra berakhir tidak ditemukan
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center">
        {{ $mitras->links() }}
    </div>

</div>
@endsection

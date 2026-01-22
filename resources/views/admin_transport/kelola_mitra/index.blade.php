@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Mitra</h4>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    {{-- TOMBOL TAMBAH --}}
    <a href="{{ url('/admin-transport/mitra/create') }}"
       class="btn btn-primary mb-3">
        Tambah Mitra
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

        <div class="col-md-5">
            <button class="btn btn-primary">Cari</button>
            <a href="{{ url('/admin-transport/mitra') }}" class="btn btn-secondary">Reset</a>
            <a href="{{ url('/admin-transport/mitra/berakhir') }}"
               class="btn btn-outline-dark">
                Ex-Mitra
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
                <th style="min-width:160px">Unit</th>
                <th>No HP</th>
                <th width="200">Kontrak</th>
                <th width="140" class="text-center">Status</th>
                <th width="320" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($mitras as $no => $mitra)
            @php
                $hariIni = now()->startOfDay();
                $aktif = is_null($mitra->kontrak_berakhir)
                    || $hariIni->lte(\Carbon\Carbon::parse($mitra->kontrak_berakhir));
            @endphp

            <tr>
                <td>{{ $mitras->firstItem() + $no }}</td>

                <td class="fw-semibold">
                    {{ $mitra->nama_mitra }}
                </td>

                {{-- UNIT --}}
                <td
                    style="
                        max-width: 180px;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    "
                    title="{{ $mitra->unit->nama_unit ?? '-' }}"
                >
                    {{ $mitra->unit->nama_unit ?? '-' }}
                </td>

                <td>{{ $mitra->no_hp }}</td>

                {{-- KONTRAK (TEKS DIPERKECIL) --}}
                <td>
                    <small class="text-muted" style="line-height:1.3;">
                        Mulai : {{ $mitra->kontrak_mulai?->format('d-m-Y') ?? '-' }}<br>
                        Selesai : {{ $mitra->kontrak_berakhir?->format('d-m-Y') ?? '-' }}
                    </small>
                </td>

                {{-- STATUS --}}
                <td class="text-center">
                    <span class="badge text-white {{ $aktif ? 'bg-success' : 'bg-danger' }}"
                          style="min-width:110px;padding:8px 0;">
                        {{ $aktif ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </td>

                {{-- AKSI --}}
                <td class="text-center">

                    <a href="{{ url('/admin-transport/mitra/'.$mitra->id) }}"
                       class="btn btn-info btn-sm me-1 mb-1">
                        Detail
                    </a>

                    <a href="{{ url('/admin-transport/mitra/'.$mitra->id.'/edit') }}"
                       class="btn btn-warning btn-sm me-1 mb-1">
                        Edit
                    </a>

                    {{-- AKHIRI SELALU MUNCUL --}}
                    <form action="{{ url('/admin-transport/mitra/'.$mitra->id.'/akhiri-kontrak') }}"
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-danger btn-sm me-1 mb-1"
                                onclick="return confirm('Yakin akhiri kontrak mitra ini?')">
                            Akhiri
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
                <td colspan="7" class="text-center">
                    Data mitra tidak ditemukan
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center">
        {{ $mitras->links() }}
    </div>

</div>
@endsection

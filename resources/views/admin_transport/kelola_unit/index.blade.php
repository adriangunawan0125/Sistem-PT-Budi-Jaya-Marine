@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Unit</h4>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- TOMBOL TAMBAH --}}
    <a href="{{ url('/admin-transport/unit/create') }}"
       class="btn btn-primary mb-3">
        Tambah Unit
    </a>

    {{-- SEARCH & FILTER --}}
    <form method="GET" class="mb-3">
        <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Cari nama unit..."
                value="{{ request('search') }}"
                style="width: 240px;"
            >

            {{-- STATUS --}}
            <select
                name="status"
                class="form-control"
                style="width: 180px;"
            >
                <option value="">-- Semua Status --</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>
                    Tersedia
                </option>
                <option value="disewakan" {{ request('status') == 'disewakan' ? 'selected' : '' }}>
                    Disewakan
                </option>
            </select>

            <button type="submit" class="btn btn-primary px-3">
                Filter
            </button>

            <a href="{{ url('/admin-transport/unit') }}"
               class="btn btn-secondary px-3">
                Reset
            </a>

        </div>
    </form>

    {{-- TABLE --}}
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th width="60">No</th>
                <th>Nama Unit</th>
                <th>Merek Mobil</th>
                <th>Status</th>
                <th>Masa Berlaku STNK</th>
                <th width="220">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($units as $no => $unit)
            <tr>
                <td>{{ $units->firstItem() + $no }}</td>

                <td class="fw-semibold">
                    {{ $unit->nama_unit }}
                </td>

                <td>{{ $unit->merek }}</td>

                {{-- STATUS --}}
                <td>
                    @if($unit->status === 'tersedia')
                        <span class="badge bg-success text-white px-3 py-2 d-inline-block text-center"
                              style="min-width:110px">
                            Tersedia
                        </span>
                    @else
                        <span class="badge bg-danger text-white px-3 py-2 d-inline-block text-center"
                              style="min-width:110px">
                            Disewakan
                        </span>
                    @endif
                </td>
{{-- MASA BERLAKU STNK --}}
<td>
    @if($unit->stnk_expired_at)
        @php
            $stnk = \Carbon\Carbon::parse($unit->stnk_expired_at)->format('Y-m-d');
            $sisa_hari = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($unit->stnk_expired_at), false);
        @endphp

        @if($sisa_hari < 0)
            <span class="badge bg-dark text-white px-3 py-2 d-inline-block text-center" style="min-width:110px">
                Expired
            </span>
        @elseif($sisa_hari <= 7)
            <span class="badge bg-danger text-white px-3 py-2 d-inline-block text-center" style="min-width:110px">
                {{ $stnk }} ({{ $sisa_hari }} hari lagi)
            </span>
        @else
            <span class="badge bg-info text-white px-3 py-2 d-inline-block text-center" style="min-width:110px">
                {{ $stnk }}
            </span>
        @endif
    @else
        <span class="text-muted">Belum diisi</span>
    @endif
</td>


                {{-- AKSI --}}
                <td>
                    <a href="{{ url('/admin-transport/unit/edit/'.$unit->id) }}"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form action="{{ url('/admin-transport/unit/delete/'.$unit->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Yakin hapus unit ini? Data tidak bisa dikembalikan!')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    Data unit tidak ditemukan
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center">
        {{ $units->links() }}
    </div>

</div>
@endsection

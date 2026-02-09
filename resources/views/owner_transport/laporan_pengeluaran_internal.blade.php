@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-5">Rekap Pengeluaran Internal</h4>

    {{-- FILTER --}}
    <form method="GET" class="mb-3">

        <div class="d-flex flex-wrap align-items-end">

            {{-- BULAN --}}
            <div class="me-3 mb-2" style="margin-right: 10px;">
                <label class="form-label mb-1">Bulan</label>
                <input type="month"
                       name="bulan"
                       value="{{ $bulan }}"
                       class="form-control"
                       style="width:170px">
            </div>

            {{-- BUTTON --}}
            <div class="mb-2">
                <button class="btn btn-primary">Filter</button>

                <a href="{{ route('pengeluaran_internal.index') }}"
                   class="btn btn-secondary">
                    Reset
                </a>

                <a href="{{ route('pengeluaran_internal.create') }}"
                   class="btn btn-success">
                    Tambah Pengeluaran
                </a>

                <a href="{{ route('owner_transport.laporan_pengeluaran', ['bulan' => $bulan]) }}"
                   class="btn btn-info">
                    Laporan Bulanan
                </a>
            </div>

        </div>
    </form>

    {{-- INFO TOTAL (DIKEMBALIKAN SEPERTI AWAL) --}}
    <div class="alert alert-info mb-4">
        Total Pemasukan:
        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
    </div>

    {{-- TABLE --}}
    <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pengeluaran as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                </td>
                <td>{{ $item->deskripsi }}</td>
                <td><b>Rp {{ number_format($item->nominal,0,',','.') }}</b></td>
                <td class="text-center">
                    <a href="{{ route('pengeluaran_internal.detail', $item->id) }}"
                       class="btn btn-sm btn-info">
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">
                    Tidak ada data pengeluaran
                </td>
            </tr>
            @endforelse

            @if($pengeluaran->count())
            <tr>
                <th colspan="3" class="text-end">TOTAL</th>
                <th>Rp {{ number_format($total,0,',','.') }}</th>
                <th></th>
            </tr>
            @endif
        </tbody>
    </table>

</div>
@endsection

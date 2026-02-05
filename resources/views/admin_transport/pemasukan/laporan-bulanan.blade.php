@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">
        Laporan Pemasukan Bulan {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}
    </h4>

    {{-- INFO TOTAL --}}
    <div class="alert alert-info mb-3">
        Total Pemasukan:
        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="mb-3">

        <input type="month"
               name="bulan"
               value="{{ $bulan }}"
               class="form-control d-inline-block me-2 mb-2"
               style="width:200px">

        <button type="submit" class="btn btn-primary me-2 mb-2">
            Tampilkan
        </button>

        <a href="{{ route('pemasukan.print.bulanan', ['bulan' => $bulan]) }}"
           target="_blank"
           class="btn btn-success me-2 mb-2">
           Print PDF
        </a>

        <a href="{{ route('pemasukan.index') }}"
           class="btn btn-secondary me-2 mb-2">
            Kembali
        </a>

    </form>

    {{-- TABLE --}}
    <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
            <tr>
                <th width="50">No</th>
                <th>Tanggal</th>
                <th>Mitra</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pemasukan as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>

                <td class="text-center">
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                </td>

                <td>
                    {{ $item->mitra->nama_mitra ?? '-' }}
                </td>

                <td class="text-center">
                    {{ ucfirst($item->kategori) }}
                </td>

                <td>
                    {{ $item->deskripsi }}
                </td>

                <td>
                    <b>Rp {{ number_format($item->nominal, 0, ',', '.') }}</b>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Data pemasukan tidak ditemukan</td>
            </tr>
            @endforelse

            <tr>
                <th colspan="5" class="text-end">TOTAL</th>
                <th>Rp {{ number_format($total,0,',','.') }}</th>
            </tr>

        </tbody>
    </table>

</div>
@endsection

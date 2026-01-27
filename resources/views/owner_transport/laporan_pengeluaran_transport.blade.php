@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Laporan Pengeluaran Transport - Bulan {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}</h4>

    {{-- FILTER BULAN --}}
    <form method="GET" class="mb-3">
        <input type="month" name="bulan" value="{{ $bulan }}" class="form-control w-auto d-inline">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
        <a href="{{ route('pengeluaran_transport.rekap') }}" class="btn btn-secondary">Kembali</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Unit</th>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $total_all = 0; @endphp
            @foreach($pengeluaran as $index => $t)
                @php
                    $unit_total = $t->items->sum('nominal');
                    $total_all += $unit_total;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $t->unit->nama_unit ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($unit_total,0,',','.') }}</td>
                    <td>
                        <a href="{{ route('pengeluaran_transport.show', $t->id) }}" class="btn btn-sm btn-info">Detail</a>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Total Keseluruhan</strong></td>
                <td colspan="2"><strong>Rp {{ number_format($total_all,0,',','.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <button onclick="window.print()" class="btn btn-success">Cetak Laporan</button>
</div>
@endsection

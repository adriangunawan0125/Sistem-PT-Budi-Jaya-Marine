@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Laporan Pengeluaran Pajak Mobil - Bulan {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}</h4>

    {{-- FILTER BULAN --}}
    <form method="GET" class="mb-3">
        <input type="month" name="bulan" value="{{ $bulan }}" class="form-control w-auto d-inline">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
        <a href="{{ route('pengeluaran_pajak.rekap') }}" class="btn btn-secondary">Kembali</a>
    </form>

    {{-- INFO TOTAL --}}
    <div class="alert alert-info mb-4">
        Total Pengeluaran Pajak:
        <strong>Rp {{ number_format($total_all, 0, ',', '.') }}</strong>
    </div>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Unit</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
                <th>Bukti</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluaran as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->unit->nama_unit ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>
                    <td>
                        @if($item->gambar)
                            <a href="{{ asset('storage/'.$item->gambar) }}" target="_blank">
                                <img src="{{ asset('storage/'.$item->gambar) }}" width="60" class="img-thumbnail">
                            </a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data pengeluaran pajak</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="4"><strong>Total Keseluruhan</strong></td>
                <td colspan="2"><strong>Rp {{ number_format($total_all,0,',','.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <button onclick="window.print()" class="btn btn-success">Cetak Laporan</button>
</div>
@endsection

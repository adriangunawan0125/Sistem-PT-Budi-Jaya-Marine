@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Laporan Pemasukan Bulan {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</h4>

    {{-- FILTER --}}
    <form method="GET" class="mb-3">
        <input type="month" name="bulan" value="{{ $bulan }}" class="form-control w-auto d-inline">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
        <a href="{{ route('pemasukan.print.bulanan', ['bulan' => $bulan]) }}"
   target="_blank"
   class="btn btn-success ">
   Print PDF
</a>

        <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

    {{-- TABLE --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemasukan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td>{{ number_format($item->nominal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>{{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- CETAK --}}
    <button onclick="window.print()" class="btn btn-success">Cetak Laporan</button>
</div>
@endsection

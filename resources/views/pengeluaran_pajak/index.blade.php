@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Pengeluaran Pajak Mobil</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-3">
        <input type="month" name="bulan" value="{{ $bulan }}" class="form-control w-auto d-inline">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('pengeluaran_pajak.create') }}" class="btn btn-success">Tambah Pajak</a>
        <a href="{{ route('pengeluaran_pajak.laporan', ['bulan'=>$bulan]) }}" class="btn btn-info">Laporan Bulanan</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Plat Nomor</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pajak as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->unit->nama_unit }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td>{{ number_format($item->nominal,0,',','.') }}</td>
                <td>
                    <a href="{{ route('pengeluaran_pajak.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('pengeluaran_pajak.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4"><strong>Total</strong></td>
                <td colspan="2"><strong>{{ number_format($total,0,',','.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

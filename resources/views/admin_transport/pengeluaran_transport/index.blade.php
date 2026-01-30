@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Pengeluaran Transport</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter Bulan & Tombol --}}
    <form method="GET" class="mb-3">
        <input type="month" name="bulan" value="{{ $bulan }}" class="form-control w-auto d-inline">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('pengeluaran_transport.create') }}" class="btn btn-success">Tambah Pengeluaran</a>
        <a href="{{ route('pengeluaran_transport.laporan',['bulan'=>$bulan]) }}" class="btn btn-info">Laporan Bulanan</a>
    </form>

    {{-- Tabel Pengeluaran --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Plat Nomor</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Nominal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transport as $index => $t)
                @foreach($t->items as $itemIndex => $item)
                    <tr>
                        @if($itemIndex == 0)
                            <td rowspan="{{ $t->items->count() }}">{{ $index + 1 }}</td>
                            <td rowspan="{{ $t->items->count() }}">{{ $t->unit->nama_unit }}</td>
                            <td rowspan="{{ $t->items->count() }}">{{ \Carbon\Carbon::parse($t->tanggal)->format('d-m-Y') }}</td>
                        @endif
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ number_format($item->nominal,0,',','.') }}</td>
                        @if($itemIndex == 0)
                            <td rowspan="{{ $t->items->count() }}">
                                <a href="{{ route('pengeluaran_transport.edit', $t->id) }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                                <form action="{{ route('pengeluaran_transport.destroy', $t->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach

                {{-- Total per unit --}}
                <tr>
                    <td colspan="3"><strong>Total {{ $t->unit->nama_unit }}</strong></td>
                    <td colspan="2"><strong>{{ number_format($t->total_amount,0,',','.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data pengeluaran transport</td>
                </tr>
            @endforelse

            {{-- Total Keseluruhan --}}
            <tr>
                <td colspan="4"><strong>Total Keseluruhan</strong></td>
                <td colspan="2"><strong>{{ number_format($total_all,0,',','.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

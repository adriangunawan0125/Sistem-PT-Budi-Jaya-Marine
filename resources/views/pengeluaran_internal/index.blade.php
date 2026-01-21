@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Rekap Pengeluaran Internal</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-3 d-flex align-items-center flex-wrap gap-2">
        <input type="month" name="bulan" value="{{ $bulan }}" class="form-control w-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('pengeluaran_internal.create') }}" class="btn btn-success">Tambah Pengeluaran</a>
        <a href="{{ route('pengeluaran_internal.laporan') }}" class="btn btn-info text-white">Laporan Pengeluaran</a>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Nominal</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengeluaran as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td class="text-end">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($item->gambar)
                            <img src="{{ asset('storage/'.$item->gambar) }}" alt="Gambar" width="50" class="img-thumbnail">
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('pengeluaran_internal.edit', $item->id) }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                        <form action="{{ route('pengeluaran_internal.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                <tr class="table-secondary">
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td colspan="3" class="text-end"><strong>{{ number_format($total, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

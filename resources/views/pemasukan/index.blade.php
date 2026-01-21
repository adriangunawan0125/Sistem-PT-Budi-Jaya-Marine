@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Rekap Pemasukan Transport</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-3">
        <input type="month"
               name="bulan"
               value="{{ request('bulan') }}"
               class="form-control w-auto d-inline">

        <button type="submit" class="btn btn-primary">
            Filter
        </button>

        <a href="{{ route('pemasukan.create') }}" class="btn btn-success">
            Tambah Pemasukan
        </a>

        <a href="{{ route('pemasukan.laporan.harian') }}" class="btn btn-info">
            Laporan Harian
        </a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th width="120">Gambar</th>
                <th>Nominal</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>
        <tbody>

            @forelse($pemasukan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td class="text-center">
                    @if($item->gambar)
                        <img src="{{ asset('storage/pemasukan/'.$item->gambar) }}"
                             width="80"
                             class="img-thumbnail">
                    @else
                        -
                    @endif
                </td>
                <td>{{ number_format($item->nominal, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('pemasukan.edit', $item->id) }}"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form action="{{ route('pemasukan.destroy', $item->id) }}"
                          method="POST"
                          style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">
                    Data pemasukan tidak ditemukan
                </td>
            </tr>
            @endforelse

            <tr>
                <td colspan="4"><strong>Total</strong></td>
                <td colspan="2">
                    <strong>{{ number_format($total, 0, ',', '.') }}</strong>
                </td>
            </tr>

        </tbody>
    </table>
</div>
@endsection

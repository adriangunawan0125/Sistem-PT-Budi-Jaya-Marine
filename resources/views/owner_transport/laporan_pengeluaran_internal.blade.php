@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Rekap Pengeluaran Internal</h4>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER --}}
    <form method="GET" class="mb-3">
        <input
            type="month"
            name="bulan"
            value="{{ $bulan }}"
            class="form-control w-auto d-inline"
        >

        <button type="submit" class="btn btn-primary">
            Filter
        </button>

        <a href="{{ route('pengeluaran_internal.create') }}" class="btn btn-success">
            Tambah Pengeluaran
        </a>

        <a href="{{ route('owner_transport.laporan_pengeluaran', ['bulan' => $bulan]) }}"
           class="btn btn-info">
            Laporan Bulanan
        </a>
    </form>
{{-- INFO TOTAL --}}
    <div class="alert alert-info mb-4">
        Total Pemasukan:
        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
    </div>
    {{-- TABEL --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
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
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td>
                        @if($item->gambar)
                            <img
                                src="{{ asset('storage/'.$item->gambar) }}"
                                alt="Gambar"
                                width="80"
                                class="img-thumbnail"
                            >
                        @else
                            -
                        @endif
                    </td>
                  
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Tidak ada data pengeluaran
                    </td>
                </tr>
            @endforelse

            @if($pengeluaran->count())
                <tr>
                    <td colspan="3" class="text-end">
                        <strong>Total</strong>
                    </td>
                    <td colspan="3">
                        <strong>
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

</div>
@endsection

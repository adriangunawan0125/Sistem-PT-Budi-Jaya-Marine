@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Laporan Pemasukan Harian</h4>

    {{-- Info total --}}
    <div class="alert alert-info mb-3">
        Total Pemasukan: <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
    </div>

    {{-- Tombol aksi --}}
    <div class="mb-3">
        <a href="{{ route('pemasukan.laporan.harian', ['tanggal' => $tanggal ?? date('Y-m-d')]) }}"
           class="btn btn-primary me-2 mb-2">
            Refresh
        </a>
        <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary mb-2">
            Kembali ke Rekap Bulanan
        </a>
    </div>

    {{-- Filter tanggal --}}
    <form method="GET" class="mb-4">
        <label for="tanggal" class="me-2">Pilih Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal ?? date('Y-m-d') }}" class="form-control d-inline w-auto me-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    {{-- Tabel laporan --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemasukan as $no => $item)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td class="text-center">
                        @if($item->gambar && file_exists(storage_path('app/public/'.$item->gambar)))
                            <img src="{{ asset('storage/'.$item->gambar) }}"
                                 width="80" alt="Bukti {{ $item->deskripsi }}">
                        @else
                            -
                        @endif
                    </td>
                    <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data untuk tanggal ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

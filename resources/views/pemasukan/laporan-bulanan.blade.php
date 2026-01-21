@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <h4 class="mb-4 text-secondary fw-semibold">
        Laporan Pemasukan Bulanan
    </h4>

    {{-- INFO TOTAL --}}
    <div class="alert alert-info mb-4">
        Total Pemasukan:
        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="mb-4">
        <input type="month"
               name="bulan"
               value="{{ request('bulan', date('Y-m')) }}"
               class="form-control d-inline-block me-2 mb-2"
               style="width:180px">

        <button type="submit"
                class="btn btn-primary me-2 mb-2">
            Filter
        </button>

        <a href="{{ route('pemasukan.index') }}"
           class="btn btn-secondary me-2 mb-2">
            Kembali
        </a>
    </form>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th class="text-center" width="120">Gambar</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pemasukan as $item)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                            <td class="text-muted">{{ $item->deskripsi }}</td>
                            <td class="text-center">
                                @if($item->gambar)
                                    {{-- ⬇️ DISAMAIN DENGAN INDEX --}}
                                    <img src="{{ asset('storage/pemasukan/'.$item->gambar) }}"
                                         width="70"
                                         class="rounded">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="fw-semibold">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Tidak ada data untuk bulan ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
@endsection

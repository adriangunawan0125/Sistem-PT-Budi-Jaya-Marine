@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Laporan Pemasukan Bulanan</h3>

  
    {{-- FILTER BULAN & TAHUN --}}
    <form method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <select name="bulan" class="form-control">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}"
                            {{ request('bulan', date('n')) == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <input type="number"
                       name="tahun"
                       class="form-control"
                       value="{{ request('tahun', date('Y')) }}"
                       placeholder="Tahun">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary">
                    Filter
                </button>
            </div>
        </div>
    </form>
  {{-- INFO TOTAL --}}
    <div class="alert alert-info mb-4">
        Total Pemasukan:
        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
    </div>
    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th width="100" class="text-center">Bukti</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pemasukan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                            </td>
                            <td>{{ $item->deskripsi }}</td>
                            <td class="text-center">
                                @if($item->gambar)
                                    {{-- PENTING: PATH SUDAH BENAR --}}
                                    <img src="{{ asset('storage/pemasukan/'.$item->gambar) }}"
                                         width="60"
                                         class="rounded">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Data tidak ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">
                                Total
                            </th>
                            <th>
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>

                </table>

            </div>
        </div>
    </div>

</div>
@endsection

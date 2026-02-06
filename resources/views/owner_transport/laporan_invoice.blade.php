@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Data Invoice</h4>

    {{-- SEARCH --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama mitra..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Cari</button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('invoice.rekap') }}"
               class="btn btn-secondary w-100">
                Reset
            </a>
        </div>
    </form>
    <div class="alert alert-info mb-3">
    Total Invoice: <strong>Rp {{ number_format($total_all, 0, ',', '.') }}</strong>
</div>
 in

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Nama Mitra</th>
                    <th>Total Tagihan</th>
                    <th>TF Terakhir</th>       
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                <tr>
                    <td>{{ $data->firstItem() + $index }}</td>

                    <td>{{ $row->nama_mitra }}</td>

                    <td>
                        @if($row->total_amount > 0)
                            Rp {{ number_format($row->total_amount, 0, ',', '.') }}
                        @else
                            <span class="text-muted">Rp 0</span>
                        @endif
                    </td>
                    <td>
    @if($row->tanggal_tf_terakhir)
        {{ \Carbon\Carbon::parse($row->tanggal_tf_terakhir)->format('d-m-Y') }}
    @else
        <span class="text-muted">-</span>
    @endif
</td>

                    <td>
         <a href="{{ route('invoiceowner.show', $row->id) }}"
   class="btn btn-info btn-sm">
    Detail
</a>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">
                        Data tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

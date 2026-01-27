@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Detail Invoice - {{ $invoice->mitra->nama_mitra ?? '-' }}</h4>

    <div class="card mb-3">
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="200">Tanggal</th>
                    <td>: {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>: <strong>{{ ucfirst($invoice->status) }}</strong></td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td>: <strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <h5>Item Invoice</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Tanggal</th>
                    <th>Cicilan</th>
                    <th>Tagihan</th>
                    <th>Amount</th>
                    <th>Bukti Trip</th>
                    <th>Bukti Transfer</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->item }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($item->cicilan,0,',','.') }}</td>
                    <td>Rp {{ number_format($item->tagihan,0,',','.') }}</td>
                    <td>Rp {{ number_format($item->amount,0,',','.') }}</td>
                    <td>
                        @if($item->gambar_trip)
                        <a href="{{ asset('storage/'.$item->gambar_trip) }}" target="_blank">
                            <img src="{{ asset('storage/'.$item->gambar_trip) }}" width="60" class="img-thumbnail">
                        </a>
                        @else - @endif
                    </td>
                    <td>
                        @if($item->gambar_transfer)
                        <a href="{{ asset('storage/'.$item->gambar_transfer) }}" target="_blank">
                            <img src="{{ asset('storage/'.$item->gambar_transfer) }}" width="60" class="img-thumbnail">
                        </a>
                        @else - @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada item</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('invoice.rekap') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection

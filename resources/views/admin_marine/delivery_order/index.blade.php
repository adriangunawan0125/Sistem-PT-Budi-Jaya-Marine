@extends('layouts.app')

@section('content')
<div class="container">

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
        Delivery Order - PO {{ $poMasuk->no_po_klien }}
    </h4>

    <div>
        <a href="{{ route('po-masuk.show', $poMasuk->id) }}"
           class="btn btn-secondary btn-sm">
            ← Kembali ke PO
        </a>

        <a href="{{ route('delivery-order.create', $poMasuk->id) }}"
           class="btn btn-warning btn-sm ms-2">
            + Buat DO
        </a>
    </div>
</div>

{{-- ================= TABLE ================= --}}
<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th width="60">No</th>
                    <th>No DO</th>
                    <th width="150">Tanggal</th>
                    <th width="120">Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveryOrders as $index => $do)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $do->no_do }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($do->tanggal_do)->format('d M Y') }}
                    </td>
                    <td>
                        @if($do->status == 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @else
                            <span class="badge bg-success">Delivered</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('delivery-order.show', $do->id) }}"
                           class="btn btn-sm btn-primary">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada Delivery Order
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail Delivery Order</h4>

    <div class="d-flex gap-2">

        <a href="{{ route('delivery-order.print',$deliveryOrder->id) }}"
           target="_blank"
           class="btn btn-danger btn-sm">
            Print PDF
        </a>

        <a href="{{ route('delivery-order.edit', $deliveryOrder->id) }}"
           class="btn btn-warning btn-sm">
            Edit
        </a>

        <form action="{{ route('delivery-order.destroy', $deliveryOrder->id) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus DO ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm">
                Hapus
            </button>
        </form>

        <a href="{{ route('po-masuk.show', $deliveryOrder->po_masuk_id) }}"
           class="btn btn-secondary btn-sm">
            ← Kembali
        </a>
        @if($deliveryOrder->status == 'draft')
<form action="{{ route('delivery-order.deliver',$deliveryOrder->id) }}"
      method="POST">
    @csrf
    @method('PATCH')
    <button class="btn btn-success btn-sm">
        tandai DO
    </button>
</form>
@endif


    </div>
</div>


{{-- ================= INFO CARD ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">

        <div class="row mb-4">

            <div class="col-md-6 mb-3">
                <strong>No DO</strong><br>
                {{ $deliveryOrder->no_do }}
            </div>

            <div class="col-md-6 mb-3 text-md-end">
                <strong>Tanggal</strong><br>
                {{ \Carbon\Carbon::parse($deliveryOrder->tanggal_do)->format('d M Y') }}
            </div>

            <div class="col-md-6 mb-3">
                <strong>Perusahaan (Client)</strong><br>
                {{ $deliveryOrder->poMasuk->mitra_marine ?? '-' }}
            </div>

            <div class="col-md-6 mb-3">
                <strong>Vessel</strong><br>
                {{ $deliveryOrder->poMasuk->vessel ?? '-' }}
            </div>

            <div class="col-md-6 mb-3">
                <strong>No PO Klien</strong><br>
                {{ $deliveryOrder->poMasuk->no_po_klien ?? '-' }}
            </div>

            <div class="col-md-6 mb-3">
                <strong>Status</strong><br>
                @if($deliveryOrder->status === 'draft')
                    <span class="badge text-light bg-secondary">DRAFT</span>
                @elseif($deliveryOrder->status === 'delivered')
                    <span class="badge text-light bg-success">DELIVERED</span>
                @endif
            </div>

        </div>

    </div>
</div>


{{-- ================= ITEMS ================= --}}
<div class="card shadow-sm">
    <div class="card-header fw-bold">
        Item Delivery
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th width="60">No</th>
                    <th>Item</th>
                    <th width="120">Qty</th>
                    <th width="120">Unit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveryOrder->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->item }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-center">{{ $item->unit }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Tidak ada item
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection

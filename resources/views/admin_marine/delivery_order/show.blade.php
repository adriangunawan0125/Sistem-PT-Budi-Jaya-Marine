@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Detail Delivery Order</h3>

    {{-- ===============================
        INFO PO KLIEN
    =============================== --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Informasi PO Klien
        </div>
        <div class="card-body">
            <table class="table table-sm">
                <tr>
                    <th width="200">No PO Klien</th>
                    <td>{{ $deliveryOrder->poMasuk->no_po_klien }}</td>
                </tr>
                <tr>
                    <th>Perusahaan</th>
                    <td>{{ $deliveryOrder->poMasuk->nama_perusahaan }}</td>
                </tr>
                <tr>
                    <th>Vessel</th>
                    <td>{{ $deliveryOrder->poMasuk->vessel }}</td>
                </tr>
                <tr>
                    <th>Status PO</th>
                    <td>
                        <span class="badge bg-secondary">
                            {{ strtoupper($deliveryOrder->poMasuk->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ===============================
        INFO DELIVERY ORDER
    =============================== --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Informasi Delivery Order
        </div>
        <div class="card-body">
            <table class="table table-sm">
                <tr>
                    <th width="200">No DO</th>
                    <td>{{ $deliveryOrder->no_do }}</td>
                </tr>
                <tr>
                    <th>Tanggal DO</th>
                    <td>{{ $deliveryOrder->tanggal_do }}</td>
                </tr>
                <tr>
                    <th>Status DO</th>
                    <td>
                        <span class="badge bg-{{ $deliveryOrder->status === 'delivered' ? 'success' : 'warning' }}">
                            {{ strtoupper($deliveryOrder->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ===============================
        ITEM DELIVERY
    =============================== --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Item Delivery
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th>Qty Kirim</th>
                        <th>Unit</th>
                        <th>Supplier</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deliveryOrder->items as $item)
                        <tr>
                            <td>{{ $item->item }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>
                                {{ $item->poSupplierItem->poSupplier->nama_perusahaan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                Tidak ada item delivery
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===============================
        ACTION
    =============================== --}}
    <div class="d-flex gap-2">
        <a href="{{ route('delivery-order.index', $deliveryOrder->poMasuk->id) }}"
           class="btn btn-secondary">
            Kembali
        </a>

        @if($deliveryOrder->status === 'draft')
            <form action="{{ route('delivery-order.deliver', $deliveryOrder->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin DO ini sudah delivered?')">
                @csrf
                @method('PUT')

                <button class="btn btn-success">
                    Tandai Delivered
                </button>
            </form>
        @endif
    </div>

</div>
@endsection

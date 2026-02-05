@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Detail PO Klien</h2>

    {{-- INFO PO --}}
    <table class="table table-bordered mt-3">
        <tr>
            <th width="200">No PO Klien</th>
            <td>{{ $po->no_po_klien }}</td>
        </tr>
        <tr>
            <th>Perusahaan</th>
            <td>{{ $po->nama_perusahaan }}</td>
        </tr>
        <tr>
            <th>Vessel</th>
            <td>{{ $po->vessel }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <span class="badge bg-info text-light">
                    {{ strtoupper($po->status) }}
                </span>
            </td>
        </tr>
    </table>
<a href="{{ route('delivery-order.index', $po->id) }}"
   class="btn btn-sm btn-warning">
    Delivery Order
</a>

    {{-- ITEM PO KLIEN --}}
    <h4 class="mt-4">Item PO Klien (Harga Jual)</h4>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Item</th>
                <th width="80">Qty</th>
                <th width="80">Unit</th>
                <th>Harga Jual</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($po->items as $item)
                <tr>
                    <td>{{ $item->item }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>Rp {{ number_format($item->price_jual) }}</td>
                    <td>Rp {{ number_format($item->amount) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="fw-bold">
        Total Jual:
        Rp {{ number_format($po->total_jual ?? 0) }}
    </p>

    <hr>

    {{-- PO SUPPLIER --}}
    <div class="d-flex justify-content-between align-items-center">
        <h4>PO ke Supplier (Harga Beli)</h4>

        <a href="{{ route('po-supplier.create', $po->id) }}"
           class="btn btn-sm btn-success">
            + Buat PO Supplier
        </a>
    </div>

    <table class="table table-bordered mt-2">
        <thead class="table-light">
            <tr>
                <th>Supplier</th>
                <th>No PO</th>
                <th>Total Beli</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($po->poSuppliers as $sup)
                <tr>
                    <td>{{ $sup->nama_perusahaan }}</td>
                    <td>{{ $sup->no_po_internal }}</td>
                    <td>
                        Rp {{ number_format($sup->totalBeli() ?? 0) }}
                    </td>
                    <td>
                        <span class="badge bg-info text-light">
                            {{ strtoupper($sup->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Belum ada PO Supplier
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="fw-bold">
        Total Beli:
        Rp {{ number_format($po->totalBeli() ?? 0) }}
    </p>

    <hr>

    {{-- MARGIN --}}
    <h4>Margin</h4>

    @if($po->status === 'closed')
        <table class="table table-bordered">
            <tr>
                <th width="200">Total Jual</th>
                <td>Rp {{ number_format($po->total_jual) }}</td>
            </tr>
            <tr>
                <th>Total Beli</th>
                <td>Rp {{ number_format($po->totalBeli()) }}</td>
            </tr>
            <tr>
                <th>Margin</th>
                <td class="fw-bold">
                    Rp {{ number_format($po->margin) }}
                </td>
            </tr>
        </table>

    @else
        <div class="alert alert-warning">
            Margin akan muncul setelah PO selesai.
        </div>
    @endif

</div>
@endsection

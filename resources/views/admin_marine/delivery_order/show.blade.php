@extends('layouts.app')

@section('content')
<div class="container">

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail Delivery Order</h4>

    <div class="d-flex flex-wrap gap-2">

        <form action="{{ route('delivery-order.update-status', $deliveryOrder->id) }}"
      method="POST"
      class="mb-0">
    @csrf
    @method('PATCH')

    <select name="status"
            class="form-control form-control-sm"
            onchange="this.form.submit()">

        <option value="draft"
            {{ $deliveryOrder->status == 'draft' ? 'selected' : '' }}>
            Draft
        </option>

        <option value="delivered"
            {{ $deliveryOrder->status == 'delivered' ? 'selected' : '' }}>
            Delivered
        </option>

    </select>
</form>

        <a href="{{ route('delivery-order.print',$deliveryOrder->id) }}"
           target="_blank"
           class="btn btn-danger btn-sm px-3" style="margin-left: 4px">
            Print PDF
        </a>

        <a href="{{ route('delivery-order.edit', $deliveryOrder->id) }}"
           class="btn btn-warning btn-sm px-3" style="margin-left: 4px">
            Edit
        </a>

        <form action="{{ route('delivery-order.destroy', $deliveryOrder->id) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus DO ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm px-3" style="margin-left: 4px">
                Hapus
            </button>
        </form>

        <a href="{{ route('po-masuk.show', $deliveryOrder->po_masuk_id) }}"
           class="btn btn-secondary btn-sm px-3" style="margin-left: 4px">
            ← Kembali
        </a>

    </div>
</div>


{{-- ================= INFO CARD ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body px-4 py-4">

        <div class="row g-4">

            <div class="col-md-6 mb-3">
                <div class="text-muted small mb-1">No Delivery Order</div>
                <div class="fw-semibold">{{ $deliveryOrder->no_do }}</div>
            </div>

            <div class="col-md-6 text-md-end">
                <div class="text-muted small mb-1">Tanggal</div>
                <div>{{ \Carbon\Carbon::parse($deliveryOrder->tanggal_do)->format('d M Y') }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="text-muted small mb-1">Perusahaan (Client)</div>
                <div>{{ $deliveryOrder->poMasuk->mitra_marine ?? '-' }}</div>
            </div>

            <div class="col-md-6 text-md-end">
                <div class="text-muted small mb-1">Vessel</div>
                <div>{{ $deliveryOrder->poMasuk->vessel ?? '-' }}</div>
            </div>

            <div class="col-md-6">
                <div class="text-muted small mb-1">No PO Klien</div>
                <div>{{ $deliveryOrder->poMasuk->no_po_klien ?? '-' }}</div>
            </div>

            <div class="col-md-6 text-md-end">
                <div class="text-muted small mb-2">Status</div>

                @if($deliveryOrder->status === 'draft')
                    <span class="badge text-light bg-secondary px-4 py-2">
                        DRAFT
                    </span>
                @elseif($deliveryOrder->status === 'delivered')
                    <span class="badge text-light bg-success px-4 py-2">
                        DELIVERED
                    </span>
                @endif
            </div>

        </div>

    </div>
</div>


{{-- ================= ITEMS ================= --}}
<div class="card shadow-sm">
    <div class="card-header fw-semibold">
        Item Delivery
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered mb-0 align-middle">
            <thead class="table-light text-center">
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
                    <td colspan="4" class="text-center text-muted py-4">
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

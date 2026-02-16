@extends('layouts.app')

@section('content')
<div class="container">

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail PO Supplier</h4>

    <div class="d-flex gap-2">
@if($poSupplier->status == 'draft')
<form action="{{ route('po-supplier.approve',$poSupplier->id) }}"
      method="POST" style="display:inline;">
    @csrf
    @method('PATCH')
    <button class="btn btn-success btn-sm">
        Approve
    </button>
</form>
@endif

@if($poSupplier->status == 'approved')
<form action="{{ route('po-supplier.cancel',$poSupplier->id) }}"
      method="POST" style="display:inline;">
    @csrf
    @method('PATCH')
    <button class="btn btn-danger btn-sm">
        Cancel
    </button>
</form>
@endif

        <a href="{{ route('po-supplier.print',$poSupplier->id) }}"
           target="_blank"
           class="btn btn-danger btn-sm">
           Print PDF
        </a>

        <a href="{{ route('po-masuk.show', $poSupplier->po_masuk_id) }}"
           class="btn btn-secondary btn-sm">
            ← Kembali ke PO Masuk
        </a>

        <a href="{{ route('po-supplier.edit', $poSupplier->id) }}"
           class="btn btn-warning btn-sm">
            Edit PO Supplier
        </a>

        <form action="{{ route('po-supplier.destroy', $poSupplier->id) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus PO Supplier ini?')">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm">
                Hapus
            </button>
        </form>

    </div>
</div>


{{-- ================= INFO CARD ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Supplier</strong><br>
                {{ $poSupplier->nama_perusahaan }}
            </div>

            <div class="col-md-6 text-md-end">
                <strong>No PO Internal</strong><br>
                {{ $poSupplier->no_po_internal }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Tanggal PO</strong><br>
                {{ \Carbon\Carbon::parse($poSupplier->tanggal_po)->format('d M Y') }}
            </div>

            <div class="col-md-6 text-md-end">
                <strong>Status</strong><br>
                @switch($poSupplier->status)
                    @case('draft')
                        <span class="badge bg-secondary text-light">Draft</span>
                        @break
                    @case('approved')
                        <span class="badge bg-success text-light">Approved</span>
                        @break
                    @case('cancelled')
                        <span class="badge bg-danger text-light">Cancelled</span>
                        @break
                @endswitch
            </div>
        </div>

        @if($poSupplier->alamat)
        <div class="mb-3">
            <strong>Alamat</strong><br>
            {{ $poSupplier->alamat }}
        </div>
        @endif

        <div>
            <strong>PO Masuk</strong><br>
            {{ $poSupplier->poMasuk->no_po_klien ?? '-' }}
        </div>

    </div>
</div>


{{-- ================= ITEMS ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-header fw-bold">
        Item Details
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Item</th>
                    <th width="15%">Price</th>
                    <th width="10%">Qty</th>
                    <th width="10%">Unit</th>
                    <th width="15%">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($poSupplier->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->item }}</td>
                    <td>Rp {{ number_format($item->price_beli,0,',','.') }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-center">{{ $item->unit }}</td>
                    <td>Rp {{ number_format($item->amount,0,',','.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Tidak ada item
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- ================= TERMS & CONDITIONS ================= --}}
@if($poSupplier->terms && $poSupplier->terms->count())
<div class="card mb-4 shadow-sm">
    <div class="card-header fw-bold">
        Terms & Conditions
    </div>

    <div class="card-body">
        <ol class="mb-0">
            @foreach($poSupplier->terms as $term)
                <li class="mb-2">
                    {{ $term->description }}
                </li>
            @endforeach
        </ol>
    </div>
</div>
@endif


{{-- ================= TOTAL SUMMARY ================= --}}
<div class="card shadow-sm">
    <div class="card-body text-end">

        <h6>
            Total Beli:
            Rp {{ number_format($poSupplier->total_beli,0,',','.') }}
        </h6>

        @if($poSupplier->discount_amount > 0)
        <h6>
            Discount
            @if($poSupplier->discount_type == 'percent')
                ({{ $poSupplier->discount_value }}%)
            @endif
            :
            Rp {{ number_format($poSupplier->discount_amount,0,',','.') }}
        </h6>
        @endif

        <h4 class="fw-bold mt-3">
            Grand Total:
            Rp {{ number_format($poSupplier->grand_total,0,',','.') }}
        </h4>

    </div>
</div>

</div>
@endsection

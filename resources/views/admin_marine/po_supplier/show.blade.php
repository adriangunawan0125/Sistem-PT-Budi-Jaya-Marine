@extends('layouts.app')

@section('content')
<div class="container">

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail PO Supplier</h4>

    <div class="d-flex flex-wrap gap-2">

        {{-- STATUS DROPDOWN --}}
<form action="{{ route('po-supplier.update-status', $poSupplier->id) }}"
      method="POST"
      id="statusForm"
      class="mb-0">
    @csrf
    @method('PATCH')

    <select name="status"
            id="statusSelect"
            class="form-control form-control-sm"
            onchange="handleStatusChange()">

        <option value="draft"
            {{ $poSupplier->status == 'draft' ? 'selected' : '' }}>
            Draft
        </option>

        <option value="approved"
            {{ $poSupplier->status == 'approved' ? 'selected' : '' }}>
            Approved
        </option>

        <option value="cancelled"
            {{ $poSupplier->status == 'cancelled' ? 'selected' : '' }}>
            Cancelled
        </option>

    </select>
</form>

       <button type="button"
        class="btn btn-danger btn-sm px-3"
        id="btnPrintPdf"
        style="margin-left: 4px">
    Print PDF
</button>

        <a href="{{ route('po-masuk.show', $poSupplier->po_masuk_id) }}"
           class="btn btn-secondary btn-sm px-3" style="margin-left: 4px">
            Kembali
        </a>

        <a href="{{ route('po-supplier.edit', $poSupplier->id) }}"
           class="btn btn-warning btn-sm px-3" style="margin-left: 4px">
            Edit
        </a>

       <button type="button"
        class="btn btn-danger btn-sm px-3"
        data-bs-toggle="modal"
        data-bs-target="#deleteModal"
        style="margin-left: 4px">
    Hapus
</button>

    </div>
</div>


{{-- ================= INFO CARD ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body px-4 py-4">

        <div class="row g-4">

            {{-- Supplier --}}
            <div class="col-md-6">
                <div class="mb-1 text-muted small">Supplier</div>
                <div class="fw-semibold fs-6 mb-3">
                    {{ $poSupplier->nama_perusahaan }}
                </div>
            </div>

            {{-- No PO Internal --}}
            <div class="col-md-6 text-md-end">
                <div class="mb-1 text-muted small">No PO Internal</div>
                <div class="fw-semibold fs-6">
                    {{ $poSupplier->no_po_internal }}
                </div>
            </div>

            {{-- Tanggal PO --}}
            <div class="col-md-6">
                <div class="mb-1 text-muted small">Tanggal PO</div>
                <div>
                    {{ \Carbon\Carbon::parse($poSupplier->tanggal_po)->format('d M Y') }}
                </div>
            </div>

            {{-- Status --}}
            <div class="col-md-6 text-md-end">
                <div class="mb-2 text-muted small">Status</div>

                @switch($poSupplier->status)
                    @case('draft')
                        <span class="badge bg-secondary px-4 py-2 text-light">
                            Draft
                        </span>
                        @break

                    @case('approved')
                        <span class="badge bg-success px-4 py-2 text-light">
                            Approved
                        </span>
                        @break

                    @case('cancelled')
                        <span class="badge bg-danger px-4 py-2 text-light">
                            Cancelled
                        </span>
                        @break
                @endswitch
            </div>

        </div>

        {{-- Divider --}}
        <hr class="my-4">

        {{-- Alamat --}}
        @if($poSupplier->alamat)
        <div class="mb-4">
            <div class="mb-1 text-muted small">Alamat</div>
            <div>
                {{ $poSupplier->alamat }}
            </div>
        </div>
        @endif

        {{-- PO Masuk --}}
        <div>
            <div class="mb-1 text-muted small">PO Masuk</div>
            <div class="fw-semibold">
                {{ $poSupplier->poMasuk->no_po_klien ?? '-' }}
            </div>
        </div>

    </div>
</div>


{{-- ================= ITEMS ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-light fw-bold">
        Item Details
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-light text-center">
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
                    <td class="text-end">Rp {{ number_format($item->price_beli,0,',','.') }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-center">{{ $item->unit }}</td>
                    <td class="text-end fw-semibold">
                        Rp {{ number_format($item->amount,0,',','.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        Tidak ada item
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>


{{-- ================= TERMS ================= --}}
@if($poSupplier->terms && $poSupplier->terms->count())
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-light fw-bold">
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

        <h6 class="mb-2">
            Total Beli:
            <span class="fw-semibold">
                Rp {{ number_format($poSupplier->total_beli,0,',','.') }}
            </span>
        </h6>

        @if($poSupplier->discount_amount > 0)
        <h6 class="mb-2 text-danger">
            Discount
            @if($poSupplier->discount_type == 'percent')
                ({{ $poSupplier->discount_value }}%)
            @endif
            :
            Rp {{ number_format($poSupplier->discount_amount,0,',','.') }}
        </h6>
        @endif

        <h4 class="fw-bold mt-3 text-primary">
            Grand Total:
            Rp {{ number_format($poSupplier->grand_total,0,',','.') }}
        </h4>

    </div>
</div>

</div>

{{-- PDF LOADING MODAL --}}
<div class="modal fade"
     id="pdfLoadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<div class="spinner-border text-danger mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Membuat PDF...
</div>

</div>
</div>
</div>
</div>
{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-danger"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Hapus PO Supplier?</h5>

<p class="text-muted">
Data tidak bisa dikembalikan setelah dihapus.
</p>

<form action="{{ route('po-supplier.destroy', $poSupplier->id) }}"
      method="POST">
@csrf
@method('DELETE')

<div class="d-flex justify-content-center gap-2 mt-3">
<button type="button"
        class="btn btn-secondary"
        data-bs-dismiss="modal" style="margin-right: 4px">
Batal
</button>

<button type="submit"
        class="btn btn-danger">
Hapus
</button>
</div>

</form>

</div>
</div>
</div>
</div>
{{-- STATUS LOADING MODAL --}}
<div class="modal fade"
     id="statusLoadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<div class="spinner-border text-primary mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Memperbarui Status...
</div>

</div>
</div>
</div>
</div>

@if(session('success'))
<div class="modal fade"
     id="successModal"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">

<div class="modal-body text-center py-4">

<i class="bi bi-check-circle-fill text-success"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Berhasil</h5>

<div class="text-muted mb-4">
    {{ session('success') }}
</div>

<button type="button"
        class="btn btn-primary px-4"
        data-bs-dismiss="modal">
    OK
</button>

</div>
</div>
</div>
</div>
@endif

<script>
document.addEventListener("DOMContentLoaded", function(){

    const btnPrint = document.getElementById("btnPrintPdf");
    const pdfModal = new bootstrap.Modal(
        document.getElementById("pdfLoadingModal")
    );

    if(btnPrint){

        btnPrint.addEventListener("click", function(){

            pdfModal.show();

            setTimeout(function(){

                window.open(
                    "{{ route('po-supplier.print',$poSupplier->id) }}",
                    "_blank"
                );

                pdfModal.hide();

            }, 400);

        });

    }

});
</script>
<script>
function handleStatusChange(){

    const form = document.getElementById("statusForm");

    const loadingModal = new bootstrap.Modal(
        document.getElementById("statusLoadingModal")
    );

    loadingModal.show();

    // beri sedikit delay supaya modal sempat tampil
    setTimeout(function(){
        form.submit();
    }, 250);
}
</script>
@if(session('success'))
<script>
document.addEventListener("DOMContentLoaded", function(){

    const successModal = new bootstrap.Modal(
        document.getElementById("successModal")
    );

    successModal.show();

});
</script>
@endif
@endsection

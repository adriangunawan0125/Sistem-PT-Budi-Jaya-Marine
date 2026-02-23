@extends('layouts.app')

@section('content')
<div class="container">

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail Delivery Order</h4>

    <div class="d-flex flex-wrap gap-2">

        <form action="{{ route('delivery-order.update-status', $deliveryOrder->id) }}"
              method="POST"
              id="statusForm"
              class="mb-0">
            @csrf
            @method('PATCH')

            <select name="status"
                    id="statusSelect"
                    class="form-control form-control-sm">

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

        <button type="button"
                class="btn btn-danger btn-sm px-3"
                id="btnPrintPdf"
                style="margin-left: 4px">
            Print PDF
        </button>

        <a href="{{ route('delivery-order.edit', $deliveryOrder->id) }}"
           class="btn btn-warning btn-sm px-3"
           style="margin-left: 4px">
            Edit
        </a>

        <button type="button"
                class="btn btn-danger btn-sm px-3"
                data-bs-toggle="modal"
                data-bs-target="#deleteModal"
                style="margin-left: 4px">
            Hapus
        </button>

        <a href="{{ route('po-masuk.show', $deliveryOrder->po_masuk_id) }}"
           class="btn btn-secondary btn-sm px-3"
           style="margin-left: 4px">
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


{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-danger"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Hapus Delivery Order?</h5>
<p class="text-muted">Data tidak bisa dikembalikan setelah dihapus.</p>

<form action="{{ route('delivery-order.destroy', $deliveryOrder->id) }}"
      method="POST">
@csrf
@method('DELETE')

<div class="d-flex justify-content-center gap-2 mt-3">
<button type="button"
        class="btn btn-secondary"
        data-bs-dismiss="modal"
        style="margin-right: 4px">
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


{{-- SUCCESS MODAL --}}
@if(session('success'))
<div class="modal fade" id="successModal" tabindex="-1">
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
<div class="fw-semibold">Membuat PDF...</div>
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
<div class="fw-semibold">Memperbarui Status...</div>
</div>
</div>
</div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function(){

    // STATUS AUTO SUBMIT (FIX)
    const statusSelect = document.getElementById("statusSelect");
    const statusForm   = document.getElementById("statusForm");
    const statusModal  = new bootstrap.Modal(
        document.getElementById("statusLoadingModal")
    );

    if(statusSelect){
        statusSelect.addEventListener("change", function(){
            statusModal.show();
            setTimeout(function(){
                statusForm.submit();
            }, 250);
        });
    }

    // PRINT PDF
    const btnPrint = document.getElementById("btnPrintPdf");
    const pdfModal = new bootstrap.Modal(
        document.getElementById("pdfLoadingModal")
    );

    if(btnPrint){
        btnPrint.addEventListener("click", function(){
            pdfModal.show();
            setTimeout(function(){
                window.open(
                    "{{ route('delivery-order.print',$deliveryOrder->id) }}",
                    "_blank"
                );
                pdfModal.hide();
            }, 400);
        });
    }

    // SUCCESS MODAL
    @if(session('success'))
        const successModal = new bootstrap.Modal(
            document.getElementById("successModal")
        );
        successModal.show();
    @endif

});
</script>

@endsection
@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Invoice</h4>

        <a href="{{ route('po-masuk.show', $invoicePo->po_masuk_id) }}"
           class="btn btn-secondary btn-sm">
            ← Kembali ke PO
        </a>
    </div>

    @php
        $colors = [
            'draft' => 'secondary',
            'issued' => 'primary',
            'paid' => 'success',
            'cancelled' => 'danger'
        ];
    @endphp

    {{-- ================= INFO CARD ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="row g-4">

                {{-- LEFT SIDE --}}
                <div class="col-md-8">

                    <div class="d-flex align-items-center mb-3" >
                        <h5 class="fw-bold mb-0" style="margin-right: 7px">
                            {{ $invoicePo->no_invoice }}
                        </h5>

                        <span class=" text-light badge bg-{{ $colors[$invoicePo->status] ?? 'secondary' }} ms-3 px-3 py-2">
                            {{ strtoupper($invoicePo->status) }}
                        </span>
                    </div>

                    <div class="row gy-3">

                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Tanggal</small>
                            <div>
                                {{ \Carbon\Carbon::parse($invoicePo->tanggal_invoice)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Periode</small>
                            <div>{{ $invoicePo->periode ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Authorization</small>
                            <div>{{ $invoicePo->authorization_no ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Vessel</small>
                            <div>{{ $invoicePo->poMasuk->vessel ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">PO No</small>
                            <div>{{ $invoicePo->poMasuk->no_po_klien ?? '-' }}</div>
                        </div>

                    </div>

                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-md-4">

                    {{-- STATUS DROPDOWN --}}
                    <form action="{{ route('invoice-po.update-status', $invoicePo->id) }}" id="statusForm"
                          method="POST"
                          class="mb-4">
                        @csrf
                        @method('PATCH')

                        <label class="form-label small mb-1">
                            Ubah Status
                        </label>

                       <select name="status"
        class="form-control form-control-sm"
        id="statusSelect">

                            <option value="draft"
                                {{ $invoicePo->status == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="issued"
                                {{ $invoicePo->status == 'issued' ? 'selected' : '' }}>
                                Issued
                            </option>

                            <option value="paid"
                                {{ $invoicePo->status == 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>

                            <option value="cancelled"
                                {{ $invoicePo->status == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>
                    </form>

                    {{-- ACTION BUTTONS --}}
                   <div class="d-flex flex-column gap-2">

    <a href="{{ route('invoice-po.print', $invoicePo->id) }}"
       target="_blank"
      class="btn btn-danger btn-sm w-100 mb-1"
id="printBtn">
        Print Invoice
    </a>

    <a href="{{ route('invoice-po.edit', $invoicePo->id) }}"
       class="btn btn-warning btn-sm w-100 mb-1">
        Edit Invoice
    </a>

    <form action="{{ route('invoice-po.destroy', $invoicePo->id) }}" 
      id="deleteForm"
      method="POST"
      class="w-100">
        @csrf
        @method('DELETE')
       <button type="button"
        class="btn btn-danger btn-sm w-100"
        data-bs-toggle="modal"
        data-bs-target="#deleteModal">
    Hapus Invoice
</button>
    </form>

</div>


                </div>

            </div>

        </div>
    </div>


    {{-- ================= ITEM TABLE ================= --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Description</th>
                            <th width="80">Qty</th>
                            <th width="100">Unit</th>
                            <th width="150">Price</th>
                            <th width="150">Amount</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($invoicePo->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-center">{{ $item->unit }}</td>
                            <td class="text-end">
                                Rp {{ number_format($item->price,0,',','.') }}
                            </td>
                            <td class="text-end fw-semibold">
                                Rp {{ number_format($item->amount,0,',','.') }}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>

        {{-- ================= SUMMARY ================= --}}
        <div class="card-footer bg-white">
            <div class="row justify-content-end">
                <div class="col-md-4">

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>
                            Rp {{ number_format($invoicePo->subtotal,0,',','.') }}
                        </span>
                    </div>

                    @if($invoicePo->discount_amount > 0)
                    <div class="d-flex justify-content-between text-danger mb-2">
                        <span>Discount</span>
                        <span>
                            - Rp {{ number_format($invoicePo->discount_amount,0,',','.') }}
                        </span>
                    </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span>Grand Total</span>
                        <span>
                            Rp {{ number_format($invoicePo->grand_total,0,',','.') }}
                        </span>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
<div class="modal fade"
     id="pdfModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<div class="spinner-border text-danger mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Membuat PDF Invoice...
</div>

</div>
</div>
</div>
</div>

<div class="modal fade"
     id="statusModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<div class="spinner-border text-primary mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Memperbarui Status Invoice...
</div>

</div>
</div>
</div>
</div>


{{-- DELETE CONFIRM MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">

<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-circle-fill text-danger"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Hapus Invoice?</h5>

<div class="text-muted mb-4">
Data invoice akan dihapus permanen.
</div>

<div class="d-flex justify-content-center gap-3">
<button class="btn btn-secondary px-4"
        data-bs-dismiss="modal" style="margin-right: 4px">
Batal
</button>

<button class="btn btn-danger px-4"
        id="confirmDelete">
Ya, Hapus
</button>
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
        class="btn btn-success px-4"
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

    const pdfModal = new bootstrap.Modal(
        document.getElementById("pdfModal")
    );

    const statusModal = new bootstrap.Modal(
        document.getElementById("statusModal")
    );

    const deleteModal = new bootstrap.Modal(
        document.getElementById("deleteModal")
    );

    // ================= PRINT PDF =================
    const printBtn = document.getElementById("printBtn");

    if(printBtn){
        printBtn.addEventListener("click", function(e){
            e.preventDefault();
            pdfModal.show();

            setTimeout(()=>{
                window.open(this.href, "_blank");
                pdfModal.hide();
            }, 600);
        });
    }

    // ================= UPDATE STATUS =================
    const statusForm = document.getElementById("statusForm");
    const statusSelect = document.getElementById("statusSelect");

    if(statusForm && statusSelect){
        statusSelect.addEventListener("change", function(){
            statusModal.show();
            setTimeout(()=>{
                statusForm.submit();
            }, 500);
        });
    }

    // ================= DELETE =================
    const confirmDelete = document.getElementById("confirmDelete");
    const deleteForm = document.getElementById("deleteForm");

    if(confirmDelete && deleteForm){
        confirmDelete.addEventListener("click", function(){
            deleteModal.hide();
            setTimeout(()=>{
                deleteForm.submit();
            },200);
        });
    }

    // ================= SUCCESS MODAL =================
@if(session('success'))
    const successModal = new bootstrap.Modal(
        document.getElementById("successModal")
    );
    successModal.show();
@endif

});
</script>
@endsection

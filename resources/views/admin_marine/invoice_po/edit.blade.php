@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit Invoice</h4>

<form action="{{ route('invoice-po.update',$invoicePo->id) }}" method="POST"  id="updateInvoiceForm">
@csrf
@method('PUT')

<div class="card shadow-sm mb-4">
<div class="card-body">

{{-- ================= HEADER ================= --}}
<div class="row g-3 mb-3">

    <div class="col-md-4">
        <label class="form-label small">No Invoice</label>
        <input type="text"
               name="no_invoice"
               value="{{ $invoicePo->no_invoice }}"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Tanggal</label>
        <input type="date"
               name="tanggal_invoice"
               value="{{ $invoicePo->tanggal_invoice }}"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Periode</label>
        <input type="text"
               name="periode"
               value="{{ $invoicePo->periode }}"
               class="form-control form-control-sm">
    </div>

</div>

<div class="row g-3 mb-3">

    <div class="col-md-6">
        <label class="form-label small">Authorization No</label>
        <input type="text"
               name="authorization_no"
               value="{{ $invoicePo->authorization_no }}"
               class="form-control form-control-sm">
    </div>

    <div class="col-md-6">
        <label class="form-label small">Manpower</label>
        <input type="text"
               name="manpower"
               value="{{ $invoicePo->manpower }}"
               class="form-control form-control-sm">
    </div>

</div>

<hr>


{{-- ================= ITEMS ================= --}}
<h6 class="mb-3">Item Invoice</h6>

<div id="items-wrapper">

@foreach($invoicePo->items as $i => $item)
<div class="card mb-3 item-row shadow-sm">
<div class="card-body">

    {{-- DESCRIPTION --}}
    <div class="mb-3">
        <label class="form-label small">Description</label>
        <textarea name="items[{{ $i }}][description]"
                  class="form-control"
                  rows="3"
                  placeholder="Deskripsi item...">{{ $item->description }}</textarea>
    </div>

    {{-- DETAIL ROW --}}
    <div class="row g-3 align-items-end">

        <div class="col-md-2">
            <label class="form-label small">Qty</label>
            <input type="number"
                   step="0.01"
                   name="items[{{ $i }}][qty]"
                   value="{{ $item->qty }}"
                   class="form-control qty">
        </div>

        <div class="col-md-2">
            <label class="form-label small">Unit</label>
            <input type="text"
                   name="items[{{ $i }}][unit]"
                   value="{{ $item->unit }}"
                   class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label small">Price</label>
            <input type="number"
                   step="0.01"
                   name="items[{{ $i }}][price]"
                   value="{{ $item->price }}"
                   class="form-control price">
        </div>

        <div class="col-md-3">
            <label class="form-label small">Amount</label>
            <input type="text"
                   value="{{ number_format($item->qty * $item->price, 0, ',', '.') }}"
                   class="form-control amount bg-light fw-semibold"
                   readonly>
        </div>

        <div class="col-md-2 text-end">
            <button type="button"
                    class="btn btn-danger btn-sm px-3"
                    onclick="removeItem(this)">
                Hapus Item
            </button>
        </div>

    </div>

</div>
</div>
@endforeach

</div>

<button type="button"
        onclick="addItem()"
        class="btn btn-primary btn-sm mb-3">
+ Tambah Item
</button>

<hr class="my-4">

{{-- ================= DISCOUNT ================= --}}
<div class="row g-3 align-items-end">

    <div class="col-md-3">
        <label class="form-label small">Discount Type</label>
        <select name="discount_type"
                class="form-control form-control-sm">
            <option value="">No Discount</option>
            <option value="percent"
                {{ $invoicePo->discount_type == 'percent' ? 'selected' : '' }}>
                Percent (%)
            </option>
            <option value="nominal"
                {{ $invoicePo->discount_type == 'nominal' ? 'selected' : '' }}>
                Nominal (Rp)
            </option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label small">Discount Value</label>
        <input type="number"
               step="0.01"
               name="discount_value"
               value="{{ $invoicePo->discount_value }}"
               class="form-control form-control-sm">
    </div>

</div>

</div>
</div>

<div class="d-flex justify-content gap-2 mt-4">

    <a href="{{ route('invoice-po.show', $invoicePo->id) }}"
       class="btn btn-secondary btn-sm px-4" style="margin-right: 4px">
        Kembali
    </a>

    <button type="submit"
            class="btn btn-success btn-sm px-4">
        Update Invoice
    </button>


</div>
</form>
</div>
{{-- UPDATE LOADING MODAL --}}
<div class="modal fade"
     id="updateModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<div class="spinner-border text-success mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Memperbarui Invoice...
</div>

</div>
</div>
</div>
</div>

{{-- WARNING MODAL --}}
<div class="modal fade" id="warningModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">

<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-warning"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Peringatan</h5>

<div class="text-muted mb-4">
Minimal harus memiliki 1 item invoice.
</div>

<button class="btn btn-warning px-4"
        data-bs-dismiss="modal">
OK
</button>

</div>
</div>
</div>
</div>
{{-- ================= SCRIPT ================= --}}
<script>

let index = {{ $invoicePo->items->count() }};

function addItem(){

    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
    <div class="card mb-3 item-row shadow-sm">
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label small">Description</label>
            <textarea name="items[${index}][description]"
                      class="form-control"
                      rows="3"
                      placeholder="Deskripsi item..."></textarea>
        </div>

        <div class="row g-3 align-items-end">

            <div class="col-md-2">
                <label class="form-label small">Qty</label>
                <input type="number"
                       step="0.01"
                       name="items[${index}][qty]"
                       class="form-control qty">
            </div>

            <div class="col-md-2">
                <label class="form-label small">Unit</label>
                <input type="text"
                       name="items[${index}][unit]"
                       class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label small">Price</label>
                <input type="number"
                       step="0.01"
                       name="items[${index}][price]"
                       class="form-control price">
            </div>

            <div class="col-md-3">
                <label class="form-label small">Amount</label>
                <input type="text"
                       class="form-control amount bg-light"
                       readonly>
            </div>

            <div class="col-md-2 text-end">
                <button type="button"
                        class="btn btn-danger btn-sm px-3"
                        onclick="removeItem(this)">
                    Hapus Item
                </button>
            </div>

        </div>

    </div>
    </div>
    `);

    index++;
    attachEvents();
}

function removeItem(btn){
    btn.closest('.item-row').remove();
}

function attachEvents(){

    document.querySelectorAll('.item-row').forEach(row => {

        let qty = row.querySelector('.qty');
        let price = row.querySelector('.price');
        let amount = row.querySelector('.amount');

        function calculate(){
            let q = parseFloat(qty.value) || 0;
            let p = parseFloat(price.value) || 0;
            amount.value = (q * p);
        }

        qty.oninput = calculate;
        price.oninput = calculate;
    });
}

attachEvents();

</script>
<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("updateInvoiceForm");
    if(!form) return;

    const updateModal = new bootstrap.Modal(
        document.getElementById("updateModal")
    );

    const warningModal = new bootstrap.Modal(
        document.getElementById("warningModal")
    );

    form.addEventListener("submit", function(e){

        e.preventDefault();

        // Validasi HTML biasa
        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        // Cek apakah ada item
        const items = document.querySelectorAll('.item-row');

        if(items.length === 0){
            warningModal.show();
            return;
        }

        // Jika aman → tampilkan modal update
        updateModal.show();

        setTimeout(function(){
            HTMLFormElement.prototype.submit.call(form);
        }, 400);

    });

});
</script>

@endsection

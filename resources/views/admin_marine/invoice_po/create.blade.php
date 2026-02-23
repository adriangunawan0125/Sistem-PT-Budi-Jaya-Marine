@extends('layouts.app')

@section('content')
<style>
    .filter-control {
        border-radius: 6px;
    }
</style>

<div class="container">

<h4 class="mb-4">Buat Invoice</h4>

<form action="{{ route('invoice-po.store') }}" method="POST" id="createInvoiceForm">
@csrf

<input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

<div class="card mb-4 shadow-sm">
<div class="card-body">

{{-- ================= HEADER ================= --}}
<div class="row g-3 mb-3">

    <div class="col-md-4">
        <label class="form-label small">No Invoice</label>
        <input type="text"
               name="no_invoice"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Tanggal</label>
        <input type="date"
               name="tanggal_invoice"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Periode</label>
        <input type="text"
               name="periode"
               class="form-control form-control-sm">
    </div>

</div>

<div class="row g-3 mb-3">

    <div class="col-md-6">
        <label class="form-label small">Authorization No</label>
        <input type="text"
               name="authorization_no"
               class="form-control form-control-sm">
    </div>

    <div class="col-md-6">
        <label class="form-label small">Manpower</label>
        <input type="text"
               name="manpower"
               class="form-control form-control-sm">
    </div>

</div>
<hr>

<h5 class="mb-3">Item Invoice</h5>

<div id="items-wrapper">

<div class="card mb-3 item-row shadow-sm">
<div class="card-body">

<div class="mb-3">
<label class="form-label small">Description</label>
<textarea name="items[0][description]" 
          class="form-control" 
          rows="3"
          placeholder="Deskripsi item..."></textarea>
</div>

<div class="row align-items-end">
<div class="col-md-2">
<label class="form-label small">Qty</label>
<input type="number" step="0.01" 
       name="items[0][qty]" 
       class="form-control qty">
</div>

<div class="col-md-2">
<label class="form-label small">Unit</label>
<input type="text" 
       name="items[0][unit]" 
       class="form-control">
</div>

<div class="col-md-3">
<label class="form-label small">Price</label>
<input type="number" step="0.01" 
       name="items[0][price]" 
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
        class="btn btn-danger btn-sm"
        onclick="removeItem(this)">
Hapus Item
</button>
</div>
</div>

</div>
</div>

</div>

<button type="button" onclick="addItem()" class="btn btn-sm btn-primary mb-3">
+ Tambah Item
</button>

<hr>

<div class="card-body py-3">
<div class="row align-items-end g-3">

<div class="col-md-3">
<label class="form-label small mb-1">Discount Type</label>
<select name="discount_type"
        class="form-control form-control-sm filter-control">
<option value="">No Discount</option>
<option value="percent">Percent (%)</option>
<option value="nominal">Nominal (Rp)</option>
</select>
</div>

<div class="col-md-3">
<label class="form-label small mb-1">Discount Value</label>
<input type="number"
       step="0.01"
       name="discount_value"
       class="form-control form-control-sm"
       placeholder="0">
</div>

</div>
</div>

</div>
</div>

<div class="d-flex justify-content gap-2 mt-4">

<a href="{{ route('po-masuk.show', $poMasuk->id) }}"
   class="btn btn-secondary btn-sm px-4"
   style="margin-right: 4px">
Kembali
</a>

<button type="submit"
        class="btn btn-success btn-sm px-4">
Simpan Invoice
</button>

</div>
</form>

</div>

{{-- LOADING MODAL --}}
<div class="modal fade"
     id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">
<div class="spinner-border text-success mb-3"
     style="width:3rem;height:3rem;"></div>
<div class="fw-semibold">
Menyimpan Invoice...
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

<script>
document.addEventListener("DOMContentLoaded", function(){

const form = document.getElementById("createInvoiceForm");
if(!form) return;

const loadingModal = new bootstrap.Modal(
    document.getElementById("loadingModal")
);

const warningModal = new bootstrap.Modal(
    document.getElementById("warningModal")
);

form.addEventListener("submit", function(e){

e.preventDefault();

if(!form.checkValidity()){
    form.reportValidity();
    return;
}

const items = document.querySelectorAll('.item-row');

if(items.length === 0){
    warningModal.show();
    return;
}

loadingModal.show();

setTimeout(function(){
    form.submit();
}, 400);

});

});
</script>

<script>

let index = 1;

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

<div class="row align-items-end">
<div class="col-md-2">
<label class="form-label small">Qty</label>
<input type="number" step="0.01" 
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
<input type="number" step="0.01" 
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
        class="btn btn-danger btn-sm"
        onclick="removeItem(this)">
Delete
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
amount.value = (q * p).toLocaleString('id-ID');
}

qty.oninput = calculate;
price.oninput = calculate;

});

}

attachEvents();

</script>

@endsection
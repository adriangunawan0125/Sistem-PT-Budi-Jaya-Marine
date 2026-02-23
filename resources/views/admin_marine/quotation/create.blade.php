@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Create Quotation</h4>

@if ($errors->any())
<div class="alert alert-danger">
<ul class="mb-0">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form action="{{ route('quotations.store') }}" method="POST" id="createForm">
@csrf

{{-- ================= HEADER ================= --}}
<div class="card mb-4">
<div class="card-header"><strong>Quotation Header</strong></div>
<div class="card-body">

    <div class="row mb-3">
<div class="col-md-6">
<label>Quote No</label>
<input type="text"
       name="quote_no"
       class="form-control"
       value="{{ old('quote_no') }}"
       required>
</div>
</div>

<div class="row mb-3">
<div class="col-md-6">
<label>Mitra</label>
<input type="text"
name="mitra_name"
class="form-control"
value="{{ old('mitra_name') }}"
required>
</div>

<div class="col-md-6">
<label>Vessel</label>
<input type="text"
name="vessel_name"
class="form-control"
value="{{ old('vessel_name') }}"
required>
</div>
</div>


<div class="row mb-3">
<div class="col-md-6">
<label>Date</label>
<input type="date" name="date" class="form-control"
value="{{ date('Y-m-d') }}" required>
</div>

<div class="col-md-6">
<label>Attention</label>
<input type="text" name="attention" class="form-control">
</div>
</div>

<div class="mb-3">
<label>Project</label>
<input type="text" name="project" class="form-control">
</div>

<div class="mb-3">
<label>Place</label>
<input type="text" name="place" class="form-control">
</div>

</div>
</div>

{{-- ================= GLOBAL TYPE ================= --}}
<div class="card mb-4">
<div class="card-header">
<strong>Quotation Type</strong>
</div>
<div class="card-body">
<select id="global-type" class="form-control w-25">
<option value="basic">Basic</option>
<option value="day">Day</option>
<option value="hour">Hour</option>
<option value="day_hour">Day & Hour</option>
</select>
<small class="text-muted">Type berlaku untuk seluruh quotation</small>
</div>
</div>

{{-- ================= SUB ITEMS ================= --}}
<div id="sub-container"></div>

<button type="button" class="btn btn-primary mb-3" onclick="addSubItem()">
+ Add Sub Item
</button>

{{-- ================= DISCOUNT ================= --}}
<div class="card mt-3">
<div class="card-body">

<div class="row align-items-end">
    
    <div class="col-md-3">
        <label>Discount Type</label>
        <select name="discount_type"
                id="discount-type"
                class="form-control"
                onchange="updateGrandTotal()">
            <option value="">No Discount</option>
            <option value="percent">Percent (%)</option>
            <option value="nominal">Nominal (Rp)</option>
        </select>
    </div>

    <div class="col-md-3">
        <label>Discount Value</label>
        <input type="number"
               name="discount_value"
               id="discount-value"
               class="form-control"
               value="0"
               oninput="updateGrandTotal()">
    </div>

</div>

<div class="text-end mt-3">
    <strong>Discount Amount: Rp 
        <span id="discount-amount">0</span>
    </strong>
</div>

</div>
</div>



{{-- ================= GRAND TOTAL ================= --}}
<div class="card mt-4">
<div class="card-body text-end">
<h4>Grand Total: Rp <span id="grand-total">0</span></h4>
</div>
</div>

{{-- ================= TERMS ================= --}}
<div class="card mt-4">
<div class="card-header d-flex justify-content-between">
<strong>Terms & Conditions</strong>
<button type="button" class="btn btn-sm btn-primary"
onclick="addTerm()">+ Add Term</button>
</div>
<div class="card-body">
<div id="terms-container"></div>
</div>
</div>

<div class="text-end mt-4">
<a href="{{ route('quotations.index') }}" class="btn btn-secondary">Cancel</a>
<button type="submit" class="btn btn-success">Save Quotation</button>
</div>

</form>
</div>
<!-- LOADING MODAL -->
<div class="modal fade"
     id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3"
                     style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Menyimpan quotation...</div>
            </div>
        </div>
    </div>
</div>
<!-- WARNING MODAL (MINIMAL ITEM) -->
<div class="modal fade" id="warningItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">

                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-warning"
                       style="font-size:60px;"></i>
                </div>

                <h5 class="fw-bold mb-2">Item Belum Ditambahkan</h5>

                <div class="text-muted mb-4">
                    Anda harus menambahkan minimal 1 item sebelum menyimpan quotation.
                </div>

                <button class="btn btn-primary px-4"
                        data-bs-dismiss="modal">
                    OK
                </button>

            </div>
        </div>
    </div>
</div>

<style>
#loadingModal .modal-content{
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    height:130px;
}
</style>

<script>

let subIndex = 0;

/* ================= APPLY TYPE ================= */
function applyTypeToAll(){

let type = document.getElementById('global-type').value;

document.querySelectorAll('.sub-type-hidden').forEach(i=>{
i.value = type;
});

document.querySelectorAll('.col-day').forEach(c=>c.style.display='');
document.querySelectorAll('.col-hour').forEach(c=>c.style.display='');

if(type==='basic'){
document.querySelectorAll('.col-day').forEach(c=>c.style.display='none');
document.querySelectorAll('.col-hour').forEach(c=>c.style.display='none');
}
if(type==='day'){
document.querySelectorAll('.col-hour').forEach(c=>c.style.display='none');
}
if(type==='hour'){
document.querySelectorAll('.col-day').forEach(c=>c.style.display='none');
}
}

/* ================= ADD SUB ================= */
function addSubItem(){

let type = document.getElementById('global-type').value;

let html = `
<div class="card mb-4 sub-block" data-index="${subIndex}">
<div class="card-header d-flex justify-content-between">
<div class="w-75">
<input type="text"
name="sub_items[${subIndex}][name]"
class="form-control"
placeholder="Sub Item Name">

<input type="hidden"
name="sub_items[${subIndex}][item_type]"
value="${type}"
class="sub-type-hidden">
</div>

<button type="button"
class="btn btn-danger btn-sm"
onclick="this.closest('.sub-block').remove();updateGrandTotal();">
Delete
</button>
</div>

<div class="card-body">

<table class="table table-bordered">
<thead>
<tr>
<th>Item</th>
<th>Price</th>
<th>Qty</th>
<th>Unit</th>
<th class="col-day">Day</th>
<th class="col-hour">Hour</th>
<th>Total</th>
<th></th>
</tr>
</thead>
<tbody></tbody>
</table>

<button type="button"
class="btn btn-sm btn-success"
onclick="addItemRow(this)">
+ Add Item
</button>

<div class="text-end mt-3">
<strong>Subtotal: Rp <span class="sub-total">0</span></strong>
</div>

</div>
</div>`;

document.getElementById('sub-container')
.insertAdjacentHTML('beforeend', html);

subIndex++;
applyTypeToAll();
}

/* ================= ADD ITEM ================= */
function addItemRow(btn){

let block = btn.closest('.sub-block');
let sIndex = block.getAttribute('data-index');
let tbody = block.querySelector('tbody');
let iIndex = tbody.children.length;

let row = `
<tr>
<td><input type="text"
name="sub_items[${sIndex}][items][${iIndex}][item]"
class="form-control item-name"></td>

<td><input type="number"
name="sub_items[${sIndex}][items][${iIndex}][price]"
class="form-control price"
oninput="calculateRow(this)"></td>

<td><input type="number"
name="sub_items[${sIndex}][items][${iIndex}][qty]"
class="form-control qty"
oninput="calculateRow(this)"></td>

<td><input type="text"
name="sub_items[${sIndex}][items][${iIndex}][unit]"
class="form-control unit"></td>

<td class="col-day">
<input type="number"
name="sub_items[${sIndex}][items][${iIndex}][day]"
class="form-control day"
oninput="calculateRow(this)">
</td>

<td class="col-hour">
<input type="number"
name="sub_items[${sIndex}][items][${iIndex}][hour]"
class="form-control hour"
oninput="calculateRow(this)">
</td>

<td>
<input type="number"
class="form-control total" readonly>
</td>

<td>
<button type="button"
class="btn btn-danger btn-sm"
onclick="this.closest('tr').remove();updateGrandTotal();">
X
</button>
</td>
</tr>`;

tbody.insertAdjacentHTML('beforeend', row);
applyTypeToAll();
}

/* ================= CALCULATE ================= */
function calculateRow(input){

let type = document.getElementById('global-type').value;

let row = input.closest('tr');
let price = parseFloat(row.querySelector('.price').value)||0;
let qty = parseFloat(row.querySelector('.qty').value)||0;
let day = parseFloat(row.querySelector('.day')?.value)||0;
let hour = parseFloat(row.querySelector('.hour')?.value)||0;

let total = 0;

switch(type){
case 'day': total = price * qty * day; break;
case 'hour': total = price * qty * hour; break;
case 'day_hour': total = hour>0 ? price*qty*day*hour : price*qty*day; break;
default: total = price * qty;
}

row.querySelector('.total').value = total;

updateGrandTotal();
}

/* ================= GRAND TOTAL ================= */
function updateGrandTotal(){

let totals = document.querySelectorAll('.total');
let subtotal = 0;

totals.forEach(t=>{
    subtotal += parseFloat(t.value)||0;
});

let discountType = document.getElementById('discount-type')?.value;
let discountValue = parseFloat(document.getElementById('discount-value')?.value)||0;

let discountAmount = 0;

if(discountType === 'percent'){
    discountAmount = subtotal * discountValue / 100;
}

if(discountType === 'nominal'){
    discountAmount = discountValue;
}

let grand = subtotal - discountAmount;

if(grand < 0) grand = 0;

document.getElementById('discount-amount').innerText =
discountAmount.toLocaleString('id-ID');

document.getElementById('grand-total').innerText =
grand.toLocaleString('id-ID');
}


/* ================= TERMS ================= */
function addTerm(){
let count = document.querySelectorAll('.term-input').length;
let html = `
<div class="input-group mb-2">
<input type="text"
name="terms[${count}]"
class="form-control term-input"
placeholder="Enter term">
<button type="button"
class="btn btn-danger"
onclick="this.closest('.input-group').remove()">
X
</button>
</div>`;
document.getElementById('terms-container')
.insertAdjacentHTML('beforeend', html);
}

/* ================= INIT ================= */
document.getElementById('global-type').addEventListener('change', function(){
applyTypeToAll();
updateGrandTotal();
});

applyTypeToAll();

</script>
<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("createForm");
    if(!form) return;

    const modal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    form.addEventListener("submit", function(e){

        e.preventDefault();

        // validasi bawaan HTML
        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        // pastikan ada minimal 1 item
        const totals = document.querySelectorAll('.total');
      if(totals.length === 0){
    const warningModal = new bootstrap.Modal(
        document.getElementById("warningItemModal")
    );
    warningModal.show();
    return;
}

        modal.show();

        setTimeout(function(){
            HTMLFormElement.prototype.submit.call(form);
        }, 250);

    });

});
</script>
@endsection

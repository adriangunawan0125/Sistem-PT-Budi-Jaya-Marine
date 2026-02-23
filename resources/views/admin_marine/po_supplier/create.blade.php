@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Buat PO Supplier</h4>

<form action="{{ route('po-supplier.store') }}" method="POST" id="createForm">
@csrf

<input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

{{-- ================= INFO SUPPLIER ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-header bg-light">
<strong>Informasi Supplier</strong>
</div>
<div class="card-body">

<div class="row g-3">
<div class="col-md-6">
<label class="form-label small mb-1">Nama Perusahaan</label>
<input name="nama_perusahaan"
class="form-control form-control-sm"
required>
</div>

<div class="col-md-6">
<label class="form-label small mb-1">No PO Internal</label>
<input name="no_po_internal"
class="form-control form-control-sm"
required>
</div>

<div class="col-md-6">
<label class="form-label small mb-1">Tanggal PO</label>
<input type="date"
name="tanggal_po"
class="form-control form-control-sm"
required>
</div>

<div class="col-md-12">
<label class="form-label small mb-1">Alamat</label>
<textarea name="alamat"
class="form-control form-control-sm"
rows="2"></textarea>
</div>
</div>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-header bg-light d-flex justify-content-between align-items-center">
<strong>Items</strong>
<button type="button"
class="btn btn-primary btn-sm px-3"
onclick="addItem()">
+ Add Item
</button>
</div>

<div class="card-body p-0">
<div class="table-responsive">
<table class="table table-bordered table-hover align-middle po-table mb-0">
<thead class="table-light text-center">
<tr>
<th width="30%">Item</th>
<th width="15%">Price Beli</th>
<th width="10%">Qty</th>
<th width="10%">Unit</th>
<th width="20%">Amount</th>
<th width="5%"></th>
</tr>
</thead>
<tbody id="item-body"></tbody>
</table>
</div>
</div>
</div>

{{-- ================= DISCOUNT ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-header bg-light">
<strong>Discount</strong>
</div>
<div class="card-body">

<div class="row g-3">
<div class="col-md-3">
<select name="discount_type"
id="discount-type"
class="form-control form-control-sm filter-control"
onchange="updateTotal()">
<option value="">No Discount</option>
<option value="percent">Percent (%)</option>
<option value="nominal">Nominal (Rp)</option>
</select>
</div>

<div class="col-md-3">
<input type="number"
name="discount_value"
id="discount-value"
class="form-control form-control-sm"
placeholder="Discount value"
oninput="updateTotal()">
</div>
</div>

</div>
</div>

{{-- ================= TERMS ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-header bg-light d-flex justify-content-between align-items-center">
<strong>Terms & Conditions</strong>
<button type="button"
class="btn btn-primary btn-sm px-3 " style="margin-left: 4px"
onclick="addTerm()">
+ Add Term
</button>
</div>

<div class="card-body">
<div id="terms-container"></div>
</div>
</div>

{{-- ================= TOTAL ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-body text-end">

<h6 class="mb-1">
Total Beli:
<span class="fw-bold">
Rp <span id="total-beli">0</span>
</span>
</h6>

<h6 class="mb-1 text-danger">
Discount:
Rp <span id="discount-amount">0</span>
</h6>

<h5 class="mt-2">
Grand Total:
<span class="fw-bold text-primary">
Rp <span id="grand-total">0</span>
</span>
</h5>

</div>
</div>

<div class="text-end">
<button class="btn btn-success btn-sm px-4">
Simpan PO Supplier
</button>
     <a href="{{ route('po-masuk.show', $poMasuk->id) }}"
           class="btn btn-secondary btn-sm px-3" style="margin-left: 4px">
            Kembali
        </a>
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

<div class="spinner-border text-primary mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Menyimpan PO Supplier...
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

<i class="bi bi-exclamation-circle-fill text-warning"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Peringatan</h5>

<div class="text-muted mb-4">
Minimal harus menambahkan 1 item terlebih dahulu.
</div>

<button class="btn btn-warning px-4"
        data-bs-dismiss="modal">
OK
</button>

</div>
</div>
</div>
</div>
<style>

.po-table th,
.po-table td{
    font-size:13px;
    padding:10px 12px;
    vertical-align:middle;
}

.table-hover tbody tr:hover{
    background-color:#f5f7fa;
}

.form-control-sm{
    font-size:13px;
}

.filter-control {
    height: 38px;
    border-radius: 6px;
}

.filter-control:focus {
    box-shadow: 0 0 0 0.15rem rgba(13,110,253,.25);
}

.amount {
    text-align: right;
}

</style>


<script>

let itemIndex = 0;
let termIndex = 0;

/* ================= ITEM ================= */

function addItem(){

let tbody = document.getElementById('item-body');

let row = `
<tr>
<td>
<input type="text"
name="items[${itemIndex}][item]"
class="form-control"
required>
</td>

<td>
<input type="number"
step="0.01"
name="items[${itemIndex}][price_beli]"
class="form-control price"
oninput="calculateRow(this)"
required>
</td>

<td>
<input type="number"
step="0.01"
name="items[${itemIndex}][qty]"
class="form-control qty"
oninput="calculateRow(this)"
required>
</td>

<td>
<input type="text"
name="items[${itemIndex}][unit]"
class="form-control">
</td>

<td>
<input type="number"
class="form-control amount"
readonly>
</td>

<td>
<button type="button"
class="btn btn-danger btn-sm"
onclick="removeRow(this)">
X
</button>
</td>
</tr>
`;

tbody.insertAdjacentHTML('beforeend', row);
itemIndex++;
}

function removeRow(btn){
btn.closest('tr').remove();
updateTotal();
}

function calculateRow(input){

let row = input.closest('tr');

let price = parseFloat(row.querySelector('.price').value) || 0;
let qty   = parseFloat(row.querySelector('.qty').value) || 0;

let amount = price * qty;

row.querySelector('.amount').value = amount.toFixed(2);

updateTotal();
}

/* ================= TERMS ================= */

function addTerm(){

let container = document.getElementById('terms-container');

let html = `
<div class="input-group mb-2">
<input type="text"
name="terms[${termIndex}]"
class="form-control"
placeholder="Masukkan term...">
<button type="button"
class="btn btn-danger"
onclick="this.closest('.input-group').remove()">
X
</button>
</div>
`;

container.insertAdjacentHTML('beforeend', html);
termIndex++;
}

/* ================= TOTAL ================= */

function updateTotal(){

let total = 0;

document.querySelectorAll('.amount').forEach(a=>{
total += parseFloat(a.value) || 0;
});

let discountType = document.getElementById('discount-type').value;
let discountValue = parseFloat(document.getElementById('discount-value').value) || 0;

let discountAmount = 0;

if(discountType === 'percent'){
discountAmount = (total * discountValue) / 100;
}

if(discountType === 'nominal'){
discountAmount = discountValue;
}

let grand = total - discountAmount;
if(grand < 0) grand = 0;

document.getElementById('total-beli').innerText =
total.toLocaleString('id-ID');

document.getElementById('discount-amount').innerText =
discountAmount.toLocaleString('id-ID');

document.getElementById('grand-total').innerText =
grand.toLocaleString('id-ID');

}

</script>
<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("createForm");
    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    if(!form) return;

    form.addEventListener("submit", function(e){

        e.preventDefault();

        // Validasi HTML bawaan
        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        // Pastikan minimal 1 item
        const items = document.querySelectorAll('#item-body tr');

        if(items.length === 0){

            const warningModal = new bootstrap.Modal(
                document.getElementById("warningModal")
            );

            warningModal.show();
            return;
        }

        loadingModal.show();

        setTimeout(function(){
            HTMLFormElement.prototype.submit.call(form);
        }, 250);

    });

});
</script>

@endsection

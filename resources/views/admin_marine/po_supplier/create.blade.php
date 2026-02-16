@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Buat PO Supplier</h4>

<form action="{{ route('po-supplier.store') }}" method="POST">
@csrf

<input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

{{-- ================= INFO SUPPLIER ================= --}}
<div class="card mb-4">
<div class="card-header">
<strong>Informasi Supplier</strong>
</div>
<div class="card-body">

<input name="nama_perusahaan"
class="form-control mb-2"
placeholder="Nama perusahaan"
required>

<textarea name="alamat"
class="form-control mb-2"
placeholder="Alamat"></textarea>

<input type="date"
name="tanggal_po"
class="form-control mb-2"
required>

<input name="no_po_internal"
class="form-control mb-2"
placeholder="No PO internal"
required>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card mb-4">
<div class="card-header d-flex justify-content-between">
<strong>Items</strong>
<button type="button"
class="btn btn-sm btn-primary"
onclick="addItem()">
+ Add Item
</button>
</div>

<div class="card-body p-0">
<table class="table table-bordered mb-0">
<thead>
<tr>
<th>Item</th>
<th width="15%">Price Beli</th>
<th width="10%">Qty</th>
<th width="10%">Unit</th>
<th width="15%">Amount</th>
<th width="5%"></th>
</tr>
</thead>
<tbody id="item-body"></tbody>
</table>
</div>
</div>

{{-- ================= DISCOUNT ================= --}}
<div class="card mb-4">
<div class="card-header">
<strong>Discount</strong>
</div>
<div class="card-body">

<div class="row">
<div class="col-md-4">
<select name="discount_type"
id="discount-type"
class="form-control"
onchange="updateTotal()">
<option value="">No Discount</option>
<option value="percent">Percent (%)</option>
<option value="nominal">Nominal (Rp)</option>
</select>
</div>

<div class="col-md-4">
<input type="number"
name="discount_value"
id="discount-value"
class="form-control"
placeholder="Discount value"
oninput="updateTotal()">
</div>
</div>

</div>
</div>

{{-- ================= TERMS ================= --}}
<div class="card mb-4">
<div class="card-header d-flex justify-content-between">
<strong>Terms & Conditions</strong>
<button type="button"
class="btn btn-sm btn-primary"
onclick="addTerm()">
+ Add Term
</button>
</div>

<div class="card-body">
<div id="terms-container"></div>
</div>
</div>

{{-- ================= TOTAL ================= --}}
<div class="card mb-4">
<div class="card-body text-end">

<h5>Total Beli: Rp <span id="total-beli">0</span></h5>
<h5>Discount: Rp <span id="discount-amount">0</span></h5>
<h4>Grand Total: Rp <span id="grand-total">0</span></h4>

</div>
</div>

<div class="text-end">
<button class="btn btn-success">
Simpan PO Supplier
</button>
</div>

</form>
</div>

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

@endsection

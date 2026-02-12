@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit Quotation</h4>

{{-- ================= HEADER ================= --}}
<div class="card mb-4">
    <div class="card-header">
        <strong>Quotation Header</strong>
    </div>

    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Quote No</label>
                <input type="text" class="form-control"
                       value="{{ $quotation->quote_no }}" readonly>
            </div>

            <div class="col-md-6">
                <label>Date</label>
                <input type="date" id="date"
                       class="form-control"
                       value="{{ $quotation->date }}">
            </div>
        </div>

        <div class="mb-3">
            <label>Mitra</label>
            <input type="text" class="form-control"
                   value="{{ $quotation->mitra->nama_mitra ?? '-' }}" readonly>
        </div>

        <div class="mb-3">
            <label>Vessel</label>
            <input type="text" class="form-control"
                   value="{{ $quotation->vessel->nama_vessel ?? '-' }}" readonly>
        </div>

        <div class="mb-3">
            <label>Attention</label>
            <input type="text" id="attention"
                   class="form-control"
                   value="{{ $quotation->attention }}">
        </div>

        <div class="mb-3">
            <label>Project</label>
            <input type="text" id="project"
                   class="form-control"
                   value="{{ $quotation->project }}">
        </div>

        <div class="mb-3">
            <label>Place</label>
            <input type="text" id="place"
                   class="form-control"
                   value="{{ $quotation->place }}">
        </div>

    </div>
</div>


{{-- ================= SUB ITEMS CONTAINER ================= --}}
<div id="sub-container"></div>

<button class="btn btn-primary mb-3" onclick="addSubItem()">+ Add Sub Item</button>
<button class="btn btn-success mb-3 float-end" onclick="saveAll()">Save All</button>


{{-- ================= GRAND TOTAL ================= --}}
<div class="card mt-4">
    <div class="card-body text-end">
        <h4>Grand Total: Rp <span id="grand-total">0</span></h4>
    </div>
</div>

</div>


<script>

function addSubItem() {

let html = `
<div class="card mb-4 sub-block">
    <div class="card-header d-flex justify-content-between">

        <div class="d-flex gap-2 w-75">
            <input type="text" class="form-control sub-name" placeholder="Sub Item Name">

            <select class="form-control sub-type"
                    onchange="changeType(this)">
                <option value="">-- Type --</option>
                <option value="basic">Basic</option>
                <option value="day">Day</option>
                <option value="hour">Hour</option>
                <option value="day_hour">Day & Hour</option>
            </select>
        </div>

        <button class="btn btn-danger btn-sm"
            onclick="this.closest('.sub-block').remove();updateGrandTotal();">
            Delete
        </button>

    </div>

    <div class="card-body">

        <table class="table table-bordered item-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th class="col-day">Day</th>
                    <th class="col-hour">Hour</th>
                    <th>Total</th>
                    <th width="50"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <button class="btn btn-sm btn-success"
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
}


function changeType(select){

let block = select.closest('.sub-block');
let dayCols = block.querySelectorAll('.col-day');
let hourCols = block.querySelectorAll('.col-hour');

dayCols.forEach(c=>c.style.display='');
hourCols.forEach(c=>c.style.display='');

if(select.value==='basic'){
    dayCols.forEach(c=>c.style.display='none');
    hourCols.forEach(c=>c.style.display='none');
}
if(select.value==='day'){
    hourCols.forEach(c=>c.style.display='none');
}
if(select.value==='hour'){
    dayCols.forEach(c=>c.style.display='none');
}

// 🔥 TAMBAH INI
block.querySelectorAll('tbody tr').forEach(row => {
    calculateRow(row.querySelector('.price'));
});
}


function addItemRow(btn){

let block = btn.closest('.sub-block');
let tbody = block.querySelector('tbody');

let row = `
<tr>
<td><input type="text" class="form-control item-name"></td>
<td><input type="number" class="form-control price" oninput="calculateRow(this)"></td>
<td><input type="number" class="form-control qty" oninput="calculateRow(this)"></td>
<td><input type="text" class="form-control unit"></td>
<td class="col-day"><input type="number" class="form-control day" oninput="calculateRow(this)"></td>
<td class="col-hour"><input type="number" class="form-control hour" oninput="calculateRow(this)"></td>
<td><input type="number" class="form-control total" readonly></td>
<td>
<button class="btn btn-danger btn-sm"
onclick="this.closest('tr').remove();updateGrandTotal();">X</button>
</td>
</tr>`;

tbody.insertAdjacentHTML('beforeend', row);
}

function calculateRow(input){

let row   = input.closest('tr');
let block = input.closest('.sub-block');
let type  = block.querySelector('.sub-type').value || 'basic'; // default basic

let price = parseFloat(row.querySelector('.price').value) || 0;
let qty   = parseFloat(row.querySelector('.qty').value) || 0;
let day   = parseFloat(row.querySelector('.day')?.value) || 0;
let hour  = parseFloat(row.querySelector('.hour')?.value) || 0;

let total = 0;

switch(type){

    case 'basic':
        total = price * qty;
    break;

    case 'day':
        total = price * qty * day;
    break;

    case 'hour':
        total = price * qty * hour;
    break;

    case 'day_hour':
        total = hour > 0
            ? price * qty * day * hour
            : price * qty * day;
    break;
}

row.querySelector('.total').value = total;

updateSubTotal(row.querySelector('.total'));
}


function updateSubTotal(input){

let block = input.closest('.sub-block');
let totals = block.querySelectorAll('.total');
let sum = 0;

totals.forEach(t=>{
    sum += parseFloat(t.value)||0;
});

block.querySelector('.sub-total').innerText =
sum.toLocaleString('id-ID');

updateGrandTotal();
}


function updateGrandTotal(){

let subs = document.querySelectorAll('.sub-total');
let grand = 0;

subs.forEach(s=>{
    grand += parseFloat(s.innerText.replace(/\./g,''))||0;
});

document.getElementById('grand-total')
.innerText = grand.toLocaleString('id-ID');
}


function saveAll(){

let subBlocks = document.querySelectorAll('.sub-block');
let subItems = [];

/* HEADER DATA */
let header = {
date: document.getElementById('date').value,
attention: document.getElementById('attention').value,
project: document.getElementById('project').value,
place: document.getElementById('place').value
};

/* SUB DATA */
subBlocks.forEach(sub=>{

let items = [];

sub.querySelectorAll('tbody tr').forEach(row=>{
items.push({
item: row.querySelector('.item-name').value,
price: row.querySelector('.price').value,
qty: row.querySelector('.qty').value,
unit: row.querySelector('.unit').value,
day: row.querySelector('.day')?.value,
hour: row.querySelector('.hour')?.value,
total: row.querySelector('.total').value
});
});

subItems.push({
name: sub.querySelector('.sub-name').value,
item_type: sub.querySelector('.sub-type').value,
items: items
});
});


fetch("{{ route('quotations.bulk.save',$quotation->id) }}",{
method:'POST',
headers:{
'Content-Type':'application/json',
'X-CSRF-TOKEN':'{{ csrf_token() }}'
},
body: JSON.stringify({
header: header,
sub_items: subItems
})
})
.then(res=>res.json())
.then(res=>{
alert('Saved Successfully');
location.reload();
});
}
// Recalculate all rows when type changes
block.querySelectorAll('tbody tr').forEach(row => {
    calculateRow(row.querySelector('.price'));
});

</script>
@endsection

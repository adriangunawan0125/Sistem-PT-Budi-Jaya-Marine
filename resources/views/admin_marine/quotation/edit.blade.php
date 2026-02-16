@extends('layouts.app')

@section('content')
<form action="{{ route('quotations.update',$quotation->id) }}" method="POST">
@csrf
@method('PUT')

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
<input type="date" name="date"
class="form-control"
value="{{ $quotation->date }}">
</div>
</div>

<div class="mb-3">
<label>Mitra</label>
<input type="text"
name="mitra_name"
class="form-control"
value="{{ old('mitra_name', $quotation->mitra_name) }}"
required>
</div>

<div class="mb-3">
<label>Vessel</label>
<input type="text"
name="vessel_name"
class="form-control"
value="{{ old('vessel_name', $quotation->vessel_name) }}"
required>
</div>


<div class="mb-3">
<label>Attention</label>
<input type="text" name="attention"
class="form-control"
value="{{ $quotation->attention }}">
</div>

<div class="mb-3">
<label>Project</label>
<input type="text" name="project"
class="form-control"
value="{{ $quotation->project }}">
</div>

<div class="mb-3">
<label>Place</label>
<input type="text" name="place"
class="form-control"
value="{{ $quotation->place }}">
</div>

</div>
</div>

{{-- ================= GLOBAL TYPE (1 TYPE ONLY) ================= --}}
@php
$globalType = $quotation->subItems->first()->item_type ?? 'basic';
@endphp

<div class="card mb-4">
<div class="card-header">
<strong>Quotation Type</strong>
</div>
<div class="card-body">
<select id="global-type" class="form-control w-25">
<option value="basic" {{ $globalType=='basic'?'selected':'' }}>Basic</option>
<option value="day" {{ $globalType=='day'?'selected':'' }}>Day</option>
<option value="hour" {{ $globalType=='hour'?'selected':'' }}>Hour</option>
<option value="day_hour" {{ $globalType=='day_hour'?'selected':'' }}>Day & Hour</option>
</select>
<small class="text-muted">Type berlaku untuk seluruh quotation</small>
</div>
</div>

{{-- ================= SUB ITEMS ================= --}}
<div id="sub-container">

@foreach($quotation->subItems as $sIndex => $sub)

<div class="card mb-4 sub-block" data-index="{{ $sIndex }}">
<div class="card-header d-flex justify-content-between">

<div class="w-75">
<input type="text"
name="sub_items[{{ $sIndex }}][name]"
class="form-control"
value="{{ $sub->name }}">
<input type="hidden"
name="sub_items[{{ $sIndex }}][item_type]"
value="{{ $globalType }}"
class="sub-type-hidden">
</div>

<button type="button"
class="btn btn-danger btn-sm"
onclick="this.closest('.sub-block').remove();recalculateAll();">
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
<th width="50"></th>
</tr>
</thead>
<tbody>

@foreach($sub->items as $iIndex => $item)
<tr>
<td>
<input type="text"
name="sub_items[{{ $sIndex }}][items][{{ $iIndex }}][item]"
class="form-control item-name"
value="{{ $item->item }}">
</td>

<td>
<input type="number"
name="sub_items[{{ $sIndex }}][items][{{ $iIndex }}][price]"
class="form-control price"
value="{{ $item->price }}"
oninput="calculateRow(this)">
</td>

<td>
<input type="number"
name="sub_items[{{ $sIndex }}][items][{{ $iIndex }}][qty]"
class="form-control qty"
value="{{ $item->qty }}"
oninput="calculateRow(this)">
</td>

<td>
<input type="text"
name="sub_items[{{ $sIndex }}][items][{{ $iIndex }}][unit]"
class="form-control unit"
value="{{ $item->unit }}">
</td>

<td class="col-day">
<input type="number"
name="sub_items[{{ $sIndex }}][items][{{ $iIndex }}][day]"
class="form-control day"
value="{{ $item->day }}"
oninput="calculateRow(this)">
</td>

<td class="col-hour">
<input type="number"
name="sub_items[{{ $sIndex }}][items][{{ $iIndex }}][hour]"
class="form-control hour"
value="{{ $item->hour }}"
oninput="calculateRow(this)">
</td>

<td>
<input type="number"
class="form-control total"
value="{{ $item->total }}" readonly>
</td>

<td>
<button type="button"
class="btn btn-danger btn-sm"
onclick="this.closest('tr').remove();recalculateAll();">
X
</button>
</td>
</tr>
@endforeach

</tbody>
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
</div>

@endforeach

</div>

<button type="button"
class="btn btn-primary mb-3"
onclick="addSubItem()">
+ Add Sub Item
</button>



{{-- ================= GRAND TOTAL ================= --}}
<div class="card mt-4 mb-4">
<div class="card-body text-end">
<h4>
Grand Total: Rp
<span id="grand-total">0</span>
</h4>

</div>

</div>
<button type="submit"
    class="btn btn-primary mb-3 float-end">
    Save All
    </button>
    <a href="{{ route('quotations.index') }}" class="btn btn-danger mb-3 float-end">Cancel</a>

</div>
    </form>


<script>

let subIndex = {{ $quotation->subItems->count() }};

/* ================= CHANGE GLOBAL TYPE ================= */
document.getElementById('global-type').addEventListener('change', function(){
applyTypeToAll();
recalculateAll();
});

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
onclick="this.closest('.sub-block').remove();recalculateAll();">
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
onclick="this.closest('tr').remove();recalculateAll();">
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
recalculateAll();
}

/* ================= GRAND ================= */
function recalculateAll(){

let grand = 0;

document.querySelectorAll('.sub-block').forEach(block=>{
let subTotal = 0;
block.querySelectorAll('.total').forEach(t=>{
subTotal += parseFloat(t.value)||0;
});
block.querySelector('.sub-total').innerText =
subTotal.toLocaleString('id-ID');
grand += subTotal;
});

document.getElementById('grand-total')
.innerText = grand.toLocaleString('id-ID');
}

applyTypeToAll();
recalculateAll();

</script>

@endsection

@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit Statement of Account (SOA)</h4>

<form action="{{ route('soa.update', $soa->id) }}" method="POST">
@csrf
@method('PUT')

{{-- ================= HEADER ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label>Debtor</label>
<input type="text"
name="debtor"
class="form-control"
value="{{ old('debtor', $soa->debtor) }}"
required>
</div>

<div class="col-md-4">
<label>Address</label>
<input type="text"
name="address"
class="form-control"
value="{{ old('address', $soa->address) }}">
</div>

<div class="col-md-4">
<label>Statement Date</label>
<input type="date"
name="statement_date"
id="statement_date"
class="form-control"
value="{{ old('statement_date', \Carbon\Carbon::parse($soa->statement_date)->format('Y-m-d')) }}"
required>
</div>
</div>

<div class="row">
<div class="col-md-4">
<label>Termin</label>
<input type="text"
name="termin"
class="form-control"
value="{{ old('termin', $soa->termin) }}">
</div>
</div>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card shadow-sm">
<div class="card-body">

<h5>SOA Items</h5>

<div id="items-wrapper">

@foreach($soa->items as $index => $item)

@php $invoice = $item->invoice; @endphp

<div class="item-row border rounded p-3 mb-3">

<div class="row mb-3">

<div class="col-md-3">
<label>Invoice</label>
<select name="items[{{ $index }}][invoice_po_id]"
class="form-control invoice-select"
required>
<option value="">-- Pilih Invoice --</option>
@foreach($invoiceList as $inv)
<option value="{{ $inv->id }}"
data-date="{{ \Carbon\Carbon::parse($inv->tanggal_invoice)->format('d/m/Y') }}"
data-total="{{ $inv->grand_total }}"
{{ $inv->id == optional($invoice)->id ? 'selected' : '' }}>
{{ $inv->no_invoice }}
</option>
@endforeach
</select>
</div>

<div class="col-md-2">
<label>Invoice Date</label>
<input type="text"
class="form-control invoice-date bg-light"
value="{{ optional($invoice)->tanggal_invoice ? \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d/m/Y') : '' }}"
readonly>
</div>

<div class="col-md-2">
<label>Pending Payment</label>
<input type="text"
class="form-control pending-payment bg-light"
value="{{ optional($invoice)->grand_total ? number_format($invoice->grand_total,0,',','.') : '' }}"
readonly>
</div>

<div class="col-md-2">
<label>Acceptment Date</label>
<input type="date"
name="items[{{ $index }}][acceptment_date]"
class="form-control acceptment-date"
value="{{ $item->acceptment_date ? \Carbon\Carbon::parse($item->acceptment_date)->format('Y-m-d') : '' }}">
</div>

<div class="col-md-1">
<label>Days</label>
<input type="text"
class="form-control days bg-light"
readonly>
</div>

<div class="col-md-2 d-flex align-items-end">
<button type="button"
class="btn btn-danger btn-sm remove-item">
Hapus
</button>
</div>

</div>

<div class="row mb-3">
<div class="col-md-12">
<label>Job Details</label>
<textarea name="items[{{ $index }}][job_details]"
class="form-control"
rows="2">{{ old("items.$index.job_details", $item->job_details) }}</textarea>
</div>
</div>

<div class="row">
<div class="col-md-12">
<label>Remarks</label>
<input type="text"
name="items[{{ $index }}][remarks]"
class="form-control"
value="{{ old("items.$index.remarks", $item->remarks) }}">
</div>
</div>

</div>

@endforeach

</div>

<button type="button"
class="btn btn-secondary btn-sm mt-2"
onclick="addItem()">
+ Tambah Invoice
</button>

</div>
</div>

<button class="btn btn-success mt-3">
Update SOA
</button>

</form>
</div>

{{-- ================= SCRIPT ================= --}}
<script>

let index = {{ $soa->items->count() }};

function addItem(){

let wrapper = document.getElementById('items-wrapper');

wrapper.insertAdjacentHTML('beforeend', `
<div class="item-row border rounded p-3 mb-3">

<div class="row mb-3">

<div class="col-md-3">
<label>Invoice</label>
<select name="items[\${index}][invoice_po_id]"
class="form-control invoice-select"
required>
<option value="">-- Pilih Invoice --</option>
@foreach($invoiceList as $inv)
<option value="{{ $inv->id }}"
data-date="{{ \Carbon\Carbon::parse($inv->tanggal_invoice)->format('d/m/Y') }}"
data-total="{{ $inv->grand_total }}">
{{ $inv->no_invoice }}
</option>
@endforeach
</select>
</div>

<div class="col-md-2">
<label>Invoice Date</label>
<input type="text" class="form-control invoice-date bg-light" readonly>
</div>

<div class="col-md-2">
<label>Pending Payment</label>
<input type="text" class="form-control pending-payment bg-light" readonly>
</div>

<div class="col-md-2">
<label>Acceptment Date</label>
<input type="date"
name="items[\${index}][acceptment_date]"
class="form-control acceptment-date">
</div>

<div class="col-md-1">
<label>Days</label>
<input type="text" class="form-control days bg-light" readonly>
</div>

<div class="col-md-2 d-flex align-items-end">
<button type="button"
class="btn btn-danger btn-sm remove-item">
Hapus
</button>
</div>

</div>

<div class="row mb-3">
<div class="col-md-12">
<label>Job Details</label>
<textarea name="items[\${index}][job_details]"
class="form-control"
rows="2"></textarea>
</div>
</div>

<div class="row">
<div class="col-md-12">
<label>Remarks</label>
<input type="text"
name="items[\${index}][remarks]"
class="form-control">
</div>
</div>

</div>
`);

index++;
}

function calculateDays(input){
let row = input.closest('.item-row');
let statementDate = document.getElementById('statement_date').value;

if(statementDate && input.value){
let s = new Date(statementDate);
let a = new Date(input.value);
let diff = Math.floor((s - a) / (1000*60*60*24));
row.querySelector('.days').value = diff >= 0 ? diff : 0;
}
}

document.addEventListener('change', function(e){

if(e.target.classList.contains('invoice-select')){
let option = e.target.options[e.target.selectedIndex];
let row = e.target.closest('.item-row');
row.querySelector('.invoice-date').value = option.dataset.date ?? '';
row.querySelector('.pending-payment').value =
new Intl.NumberFormat('id-ID').format(option.dataset.total ?? 0);
}

if(e.target.classList.contains('acceptment-date')){
calculateDays(e.target);
}

if(e.target.id === 'statement_date'){
document.querySelectorAll('.acceptment-date').forEach(el=>{
if(el.value){ calculateDays(el); }
});
}

});

document.addEventListener('click', function(e){
if(e.target.classList.contains('remove-item')){
e.target.closest('.item-row').remove();
}
});

document.addEventListener('DOMContentLoaded', function(){
document.querySelectorAll('.acceptment-date').forEach(el=>{
if(el.value){ calculateDays(el); }
});
});

</script>

@endsection

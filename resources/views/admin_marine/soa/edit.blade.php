@extends('layouts.app')

@section('content')

<style>
.filter-control{
    font-size:12px;
    height:32px;
    border-radius:6px;
    border:1px solid #dee2e6;
}

.filter-control:focus{
    border-color:#0d6efd;
    box-shadow:0 0 0 0.1rem rgba(13,110,253,.15);
}
</style>

<div class="container py-4">

<h5 class="mb-4 fw-semibold">Edit Statement of Account</h5>

<form action="{{ route('soa.update', $soa->id) }}" method="POST" id="updateSoaForm">
@csrf
@method('PUT')

{{-- ================= HEADER ================= --}}
<div class="card shadow-sm mb-4">
<div class="card-body small">

<div class="row g-3">

<div class="col-md-4">
<label class="form-label small">Debtor</label>
<input type="text"
name="debtor"
class="form-control form-control-sm"
value="{{ old('debtor', $soa->debtor) }}"
required>
</div>

<div class="col-md-4">
<label class="form-label small">Address</label>
<input type="text"
name="address"
class="form-control form-control-sm"
value="{{ old('address', $soa->address) }}">
</div>

<div class="col-md-4">
<label class="form-label small">Statement Date</label>
<input type="date"
name="statement_date"
id="statement_date"
class="form-control form-control-sm"
value="{{ old('statement_date', \Carbon\Carbon::parse($soa->statement_date)->format('Y-m-d')) }}"
required>
</div>

<div class="col-md-4">
<label class="form-label small">Termin</label>
<input type="text"
name="termin"
class="form-control form-control-sm"
value="{{ old('termin', $soa->termin) }}">
</div>

</div>
</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card shadow-sm">
<div class="card-header d-flex justify-content-between align-items-center small">
<strong>SOA Items</strong>

<button type="button"
class="btn btn-primary btn-sm"
onclick="addItem()">
+ Tambah Invoice
</button>
</div>

<div class="card-body small">

<div id="items-wrapper">

@foreach($soa->items as $i => $item)
@php $invoice = $item->invoice; @endphp

<div class="item-row border rounded-3 p-3 mb-3">

<div class="row g-3 align-items-end">

<div class="col-md-3">
<label class="form-label small text-muted">Invoice</label>
<select name="items[{{ $i }}][invoice_po_id]"
class="form-control form-control-sm filter-control invoice-select"
required>

<option value="">Pilih Invoice</option>

@foreach($invoiceList as $inv)
 <option value="{{ $inv->id }}"
        data-date="{{ $inv->tanggal_invoice }}"
        data-total="{{ $inv->grand_total }}">

        {{ $inv->no_invoice }}
             -- {{ $inv->poMasuk->no_po_klien ?? '-' }}
        -- {{ $inv->poMasuk->mitra_marine ?? '-' }}
        -- {{ $inv->poMasuk->vessel ?? '-' }}
    
        -- Rp {{ number_format($inv->grand_total,0,',','.') }}

    </option>
@endforeach

</select>
</div>

<div class="col-md-2">
<label class="form-label small text-muted">Invoice Date</label>
<input type="text"
class="form-control form-control-sm invoice-date bg-white"
value="{{ optional($invoice)->tanggal_invoice ? \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d/m/Y') : '' }}"
readonly>
</div>

<div class="col-md-2">
<label class="form-label small text-muted">Pending</label>
<input type="text"
class="form-control form-control-sm pending-payment bg-white"
value="{{ optional($invoice)->grand_total ? number_format($invoice->grand_total,0,',','.') : '' }}"
readonly>
</div>

<div class="col-md-2">
<label class="form-label small text-muted">Accept Date</label>
<input type="date"
name="items[{{ $i }}][acceptment_date]"
class="form-control form-control-sm acceptment-date"
value="{{ $item->acceptment_date ? \Carbon\Carbon::parse($item->acceptment_date)->format('Y-m-d') : '' }}">
</div>

<div class="col-md-1">
<label class="form-label small text-muted">Days</label>
<input type="text"
class="form-control form-control-sm days bg-white"
readonly>
</div>

<div class="col-md-2 text-end">
<button type="button"
class="btn btn-sm btn-danger remove-item">
hapus
</button>
</div>

</div>

<div class="row g-3 mt-2">

<div class="col-md-8">
<label class="form-label small text-muted">Job Details</label>
<textarea name="items[{{ $i }}][job_details]"
class="form-control form-control-sm"
rows="2">{{ old("items.$i.job_details", $item->job_details) }}</textarea>
</div>

<div class="col-md-4">
<label class="form-label small text-muted">Remarks</label>
<input type="text"
name="items[{{ $i }}][remarks]"
class="form-control form-control-sm"
value="{{ old("items.$i.remarks", $item->remarks) }}">
</div>

</div>

</div>
@endforeach

</div>
</div>
</div>

<div class="text-end mt-4">
<button class="btn btn-success btn-sm px-4">
Update SOA
</button>
<a href="{{ route('soa.show', $soa->id) }}" class="btn btn-sm btn-secondary" style="margin-left: 4px">
    Kembali</a>
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
Memperbarui SOA...
</div>

</div>
</div>
</div>
</div>
{{-- WARNING MODAL --}}
<div class="modal fade"
     id="warningModal"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">

<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-warning"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Peringatan</h5>

<div class="text-muted mb-4">
Minimal harus ada 1 item invoice.
</div>

<button type="button"
        class="btn btn-warning px-4"
        data-bs-dismiss="modal">
    OK
</button>

</div>
</div>
</div>
</div>


{{-- ================= SCRIPT ================= --}}
<script>

let index = {{ $soa->items->count() }};

function addItem(){

let wrapper = document.getElementById('items-wrapper');

wrapper.insertAdjacentHTML('beforeend', `
<div class="item-row border rounded-3 p-3 mb-3">

<div class="row g-3 align-items-end">

<div class="col-md-3">
<label class="form-label small text-muted">Invoice</label>
<select name="items[\${index}][invoice_po_id]"
class="form-control form-control-sm filter-control invoice-select"
required>
<option value="">Pilih Invoice</option>
@foreach($invoiceList as $inv)
 <option value="{{ $inv->id }}"
        data-date="{{ $inv->tanggal_invoice }}"
        data-total="{{ $inv->grand_total }}">

        {{ $inv->no_invoice }}
             -- {{ $inv->poMasuk->no_po_klien ?? '-' }}
        -- {{ $inv->poMasuk->mitra_marine ?? '-' }}
        -- {{ $inv->poMasuk->vessel ?? '-' }}
    
        -- Rp {{ number_format($inv->grand_total,0,',','.') }}

    </option>
@endforeach
</select>
</div>

<div class="col-md-2">
<label class="form-label small text-muted">Invoice Date</label>
<input type="text"
class="form-control form-control-sm invoice-date bg-white"
readonly>
</div>

<div class="col-md-2">
<label class="form-label small text-muted">Pending</label>
<input type="text"
class="form-control form-control-sm pending-payment bg-white"
readonly>
</div>

<div class="col-md-2">
<label class="form-label small text-muted">Accept Date</label>
<input type="date"
name="items[\${index}][acceptment_date]"
class="form-control form-control-sm acceptment-date">
</div>

<div class="col-md-1">
<label class="form-label small text-muted">Days</label>
<input type="text"
class="form-control form-control-sm days bg-white"
readonly>
</div>

<div class="col-md-2 text-end">
<button type="button"
class="btn btn-sm btn-danger remove-item">
Hapus
</button>
</div>

</div>

<div class="row g-3 mt-2">

<div class="col-md-8">
<label class="form-label small text-muted">Job Details</label>
<textarea name="items[\${index}][job_details]"
class="form-control form-control-sm"
rows="2"></textarea>
</div>

<div class="col-md-4">
<label class="form-label small text-muted">Remarks</label>
<input type="text"
name="items[\${index}][remarks]"
class="form-control form-control-sm">
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
let diff = Math.floor((s - a)/(1000*60*60*24));

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

<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("updateSoaForm");
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

        const items = document.querySelectorAll(".item-row");

        if(items.length === 0){
            warningModal.show();
            return;
        }

        loadingModal.show();

        setTimeout(function(){
            HTMLFormElement.prototype.submit.call(form);
        }, 400);

    });

});
</script>
@endsection

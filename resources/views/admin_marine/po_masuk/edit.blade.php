@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit PO Masuk</h4>

@if ($errors->any())
<div class="alert alert-danger py-2">
<ul class="mb-0 small">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form action="{{ route('po-masuk.update',$poMasuk->id) }}" method="POST" id="editForm">
@csrf
@method('PUT')

{{-- ================= HEADER ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-header bg-light">
<strong>Header PO</strong>
</div>
<div class="card-body">

<div class="row g-3 mb-3">
<div class="col-md-6">
<label class="form-label small mb-1">Mitra</label>
<input type="text"
name="mitra_marine"
class="form-control form-control-sm"
value="{{ old('mitra_marine', $poMasuk->mitra_marine) }}"
required>
</div>

<div class="col-md-6">
<label class="form-label small mb-1">Vessel</label>
<input type="text"
name="vessel"
class="form-control form-control-sm"
value="{{ old('vessel', $poMasuk->vessel) }}"
required>
</div>
</div>

<div class="row g-3 mb-3">
<div class="col-md-6">
<label class="form-label small mb-1">No PO Klien</label>
<input type="text"
name="no_po_klien"
class="form-control form-control-sm"
value="{{ old('no_po_klien', $poMasuk->no_po_klien) }}"
required>
</div>

<div class="col-md-6">
<label class="form-label small mb-1">Tanggal PO</label>
<input type="date"
name="tanggal_po"
class="form-control form-control-sm"
value="{{ old('tanggal_po', $poMasuk->tanggal_po) }}"
required>
</div>
</div>

<div class="row g-3 mb-3"> 
<div class="col-md-6">
<label class="form-label small mb-1">Type</label>
<select name="type"
        class="form-control form-control-sm filter-control"
        required>
    <option value="sparepart"
        {{ $poMasuk->type == 'sparepart' ? 'selected' : '' }}>
        Sparepart
    </option>
    <option value="manpower"
        {{ $poMasuk->type == 'manpower' ? 'selected' : '' }}>
        Manpower
    </option>
</select>
</div>
</div>

<div class="mb-3">
<label class="form-label small mb-1">Alamat Project / Delivery</label>
<textarea name="alamat"
class="form-control form-control-sm"
rows="3">{{ old('alamat', $poMasuk->alamat) }}</textarea>
</div>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-header bg-light d-flex justify-content-between align-items-center">
<strong>Item PO</strong>
<button type="button"
class="btn btn-primary btn-sm px-3"
onclick="addItem()">
+ Add Item
</button>
</div>

<div class="card-body p-0">
<div class="table-responsive">
<table class="table table-bordered table-hover align-middle mb-0 po-table">
<thead class="table-light text-center">
<tr>
<th>Item</th>
<th width="15%">Price</th>
<th width="10%">Qty</th>
<th width="12%">Unit</th>
<th width="15%">Amount</th>
<th width="5%"></th>
</tr>
</thead>

<tbody id="item-body">

@foreach($poMasuk->items as $i => $item)
<tr>
<td>
<input type="text"
name="items[{{ $i }}][item]"
class="form-control form-control-sm"
value="{{ $item->item }}" required>
</td>

<td>
<input type="number"
name="items[{{ $i }}][price_jual]"
class="form-control form-control-sm price text-end"
value="{{ $item->price_jual }}"
oninput="calculateRow(this)" required>
</td>

<td>
<input type="number"
name="items[{{ $i }}][qty]"
class="form-control form-control-sm qty text-center"
value="{{ $item->qty }}"
oninput="calculateRow(this)" required>
</td>

<td>
<input type="text"
name="items[{{ $i }}][unit]"
class="form-control form-control-sm text-center"
value="{{ $item->unit }}">
</td>

<td>
<input type="number"
class="form-control form-control-sm amount text-end"
value="{{ $item->amount }}"
readonly>
</td>

<td class="text-center">
<button type="button"
class="btn btn-danger btn-sm"
onclick="this.closest('tr').remove();updateTotal();">
X
</button>
</td>
</tr>
@endforeach

</tbody>
</table>
</div>
</div>
</div>

{{-- ================= TOTAL ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-body text-end">
<h5 class="mb-0">
Total Jual: 
<span class="fw-bold text-primary">
Rp <span id="grand-total">
{{ number_format($poMasuk->total_jual,0,',','.') }}
</span>
</span>
</h5>
</div>
</div>

<div class="text-end">

        <a href="{{ route('po-masuk.show', $poMasuk->id) }}"
           class="btn btn-secondary btn-sm px-3" style="margin-left: 4px">
            Kembali
        </a>

<button type="submit"
class="btn btn-success btn-sm px-3">
Update PO
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

<div class="spinner-border text-primary mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Memperbarui data...
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
Minimal harus memiliki 1 item pada PO.
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


</style>


<script>

let itemIndex = {{ $poMasuk->items->count() }};

function addItem(){

let row = `
<tr>
<td>
<input type="text"
name="items[${itemIndex}][item]"
class="form-control" required>
</td>

<td>
<input type="number"
name="items[${itemIndex}][price_jual]"
class="form-control price"
oninput="calculateRow(this)" required>
</td>

<td>
<input type="number"
name="items[${itemIndex}][qty]"
class="form-control qty"
oninput="calculateRow(this)" required>
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
onclick="this.closest('tr').remove();updateTotal();">
X
</button>
</td>
</tr>`;

document.getElementById('item-body')
.insertAdjacentHTML('beforeend', row);

itemIndex++;
}

function calculateRow(input){

let row = input.closest('tr');

let price = parseFloat(row.querySelector('.price').value) || 0;
let qty   = parseFloat(row.querySelector('.qty').value) || 0;

let total = price * qty;

row.querySelector('.amount').value = total;

updateTotal();
}

function updateTotal(){

let total = 0;

document.querySelectorAll('.amount').forEach(a=>{
total += parseFloat(a.value) || 0;
});

document.getElementById('grand-total')
.innerText = total.toLocaleString('id-ID');
}

updateTotal();

</script>
<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("editForm");
    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );
    const warningModal = new bootstrap.Modal(
        document.getElementById("warningModal")
    );

    if(!form) return;

    form.addEventListener("submit", function(e){

        e.preventDefault();

        // Validasi HTML
        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        // Cek minimal 1 item
        const rows = document.querySelectorAll('#item-body tr');

        if(rows.length === 0){
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

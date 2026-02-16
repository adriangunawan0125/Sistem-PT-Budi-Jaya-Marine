@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit PO Masuk</h4>

@if ($errors->any())
<div class="alert alert-danger">
<ul class="mb-0">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form action="{{ route('po-masuk.update',$poMasuk->id) }}" method="POST">
@csrf
@method('PUT')

{{-- ================= HEADER ================= --}}
<div class="card mb-4">
<div class="card-header"><strong>Header PO</strong></div>
<div class="card-body">

<div class="row mb-3">
<div class="col-md-6">
<label>Mitra</label>
<input type="text"
name="mitra_marine"
class="form-control"
value="{{ old('mitra_marine', $poMasuk->mitra_marine) }}"
required>
</div>

<div class="col-md-6">
<label>Vessel</label>
<input type="text"
name="vessel"
class="form-control"
value="{{ old('vessel', $poMasuk->vessel) }}"
required>
</div>
</div>

<div class="row mb-3">
<div class="col-md-6">
<label>No PO Klien</label>
<input type="text"
name="no_po_klien"
class="form-control"
value="{{ old('no_po_klien', $poMasuk->no_po_klien) }}"
required>
</div>

<div class="col-md-6">
<label>Tanggal PO</label>
<input type="date"
name="tanggal_po"
class="form-control"
value="{{ old('tanggal_po', $poMasuk->tanggal_po) }}"
required>
</div>
</div>

<div class="row mb-3">
<div class="col-md-6">
<label>Type</label>
<select name="type" class="form-control" required>
<option value="sparepart" {{ $poMasuk->type == 'sparepart' ? 'selected' : '' }}>Sparepart</option>
<option value="manpower" {{ $poMasuk->type == 'manpower' ? 'selected' : '' }}>Manpower</option>
</select>
</div>
</div>

<div class="mb-3">
<label>Alamat Project / Delivery</label>
<textarea name="alamat"
class="form-control"
rows="3">{{ old('alamat', $poMasuk->alamat) }}</textarea>
</div>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card mb-4">
<div class="card-header d-flex justify-content-between">
<strong>Item PO</strong>
<button type="button"
class="btn btn-sm btn-primary"
onclick="addItem()">
+ Add Item
</button>
</div>

<div class="card-body p-0">
<table class="table table-bordered mb-0">
<thead class="table-dark">
<tr>
<th>Item</th>
<th width="15%">Price Jual</th>
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
class="form-control"
value="{{ $item->item }}" required>
</td>

<td>
<input type="number"
name="items[{{ $i }}][price_jual]"
class="form-control price"
value="{{ $item->price_jual }}"
oninput="calculateRow(this)" required>
</td>

<td>
<input type="number"
name="items[{{ $i }}][qty]"
class="form-control qty"
value="{{ $item->qty }}"
oninput="calculateRow(this)" required>
</td>

<td>
<input type="text"
name="items[{{ $i }}][unit]"
class="form-control"
value="{{ $item->unit }}">
</td>

<td>
<input type="number"
class="form-control amount"
value="{{ $item->amount }}"
readonly>
</td>

<td>
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

{{-- ================= TOTAL ================= --}}
<div class="card mb-4">
<div class="card-body text-end">
<h4>
Total Jual: Rp
<span id="grand-total">
{{ number_format($poMasuk->total_jual,0,',','.') }}
</span>
</h4>
</div>
</div>

<div class="text-end">
<a href="{{ route('po-masuk.index') }}"
class="btn btn-secondary">
Cancel
</a>

<button type="submit"
class="btn btn-success">
Update PO
</button>
</div>

</form>
</div>

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
@endsection

@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Buat Invoice</h4>

<form action="{{ route('invoice-po.store') }}" method="POST">
@csrf

<input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

<div class="card mb-4 shadow-sm">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label>No Invoice</label>
<input type="text" name="no_invoice" class="form-control" required>
</div>

<div class="col-md-4">
<label>Tanggal</label>
<input type="date" name="tanggal_invoice" class="form-control" required>
</div>

<div class="col-md-4">
<label>Periode</label>
<input type="text" name="periode" class="form-control">
</div>
</div>

<div class="row mb-3">
<div class="col-md-6">
<label>Authorization No</label>
<input type="text" name="authorization_no" class="form-control">
</div>

<div class="col-md-6">
<label>Manpower (Optional)</label>
<input type="text" name="manpower" class="form-control">
</div>
</div>

<hr>

<h5>Item Invoice</h5>

<div id="items-wrapper">

<div class="row mb-2 align-items-center item-row">
    <div class="col-md-3">
        <input type="text" name="items[0][description]" class="form-control" placeholder="Description">
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01" name="items[0][qty]" class="form-control qty" placeholder="Qty">
    </div>
    <div class="col-md-2">
        <input type="text" name="items[0][unit]" class="form-control" placeholder="Unit">
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01" name="items[0][price]" class="form-control price" placeholder="Price">
    </div>
    <div class="col-md-3">
        <input type="text" class="form-control amount bg-light" placeholder="Amount" readonly>
    </div>
</div>

</div>

<button type="button" onclick="addItem()" class="btn btn-sm btn-secondary mb-3">
+ Tambah Item
</button>

<hr>

<div class="row">
<div class="col-md-3">
<select name="discount_type" class="form-control">
<option value="">No Discount</option>
<option value="percent">Percent (%)</option>
<option value="nominal">Nominal</option>
</select>
</div>

<div class="col-md-3">
<input type="number" step="0.01" name="discount_value" class="form-control" placeholder="Discount Value">
</div>
</div>

</div>
</div>

<button class="btn btn-success">
Simpan Invoice
</button>

</form>

</div>

<script>
let index = 1;

function addItem(){
    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
        <div class="row mb-2 align-items-center item-row">
            <div class="col-md-3">
                <input type="text" name="items[${index}][description]" class="form-control" placeholder="Description">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="items[${index}][qty]" class="form-control qty" placeholder="Qty">
            </div>
            <div class="col-md-2">
                <input type="text" name="items[${index}][unit]" class="form-control" placeholder="Unit">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="items[${index}][price]" class="form-control price" placeholder="Price">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control amount bg-light" placeholder="Amount" readonly>
            </div>
        </div>
    `);

    index++;
    attachEvents();
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

        qty.removeEventListener('input', calculate);
        price.removeEventListener('input', calculate);

        qty.addEventListener('input', calculate);
        price.addEventListener('input', calculate);
    });
}

attachEvents();
</script>

@endsection

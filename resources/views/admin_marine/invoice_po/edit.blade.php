@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit Invoice</h4>

<form action="{{ route('invoice-po.update',$invoicePo->id) }}" method="POST">
@csrf
@method('PUT')

<div class="card mb-4 shadow-sm">
<div class="card-body">

{{-- HEADER --}}
<div class="row mb-3">
<div class="col-md-4">
<label>No Invoice</label>
<input type="text" name="no_invoice"
       value="{{ $invoicePo->no_invoice }}"
       class="form-control" required>
</div>

<div class="col-md-4">
<label>Tanggal</label>
<input type="date" name="tanggal_invoice"
       value="{{ $invoicePo->tanggal_invoice }}"
       class="form-control" required>
</div>

<div class="col-md-4">
<label>Periode</label>
<input type="text" name="periode"
       value="{{ $invoicePo->periode }}"
       class="form-control">
</div>
</div>

<div class="row mb-3">
<div class="col-md-6">
<label>Authorization No</label>
<input type="text" name="authorization_no"
       value="{{ $invoicePo->authorization_no }}"
       class="form-control">
</div>

<div class="col-md-6">
<label>Manpower</label>
<input type="text" name="manpower"
       value="{{ $invoicePo->manpower }}"
       class="form-control">
</div>
</div>

<hr>

<h5>Item Invoice</h5>

<div id="items-wrapper">

@foreach($invoicePo->items as $i => $item)
<div class="row mb-2 align-items-center item-row">
    <div class="col-md-3">
        <input type="text"
               name="items[{{ $i }}][description]"
               value="{{ $item->description }}"
               class="form-control">
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01"
               name="items[{{ $i }}][qty]"
               value="{{ $item->qty }}"
               class="form-control qty">
    </div>
    <div class="col-md-2">
        <input type="text"
               name="items[{{ $i }}][unit]"
               value="{{ $item->unit }}"
               class="form-control">
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01"
               name="items[{{ $i }}][price]"
               value="{{ $item->price }}"
               class="form-control price">
    </div>
    <div class="col-md-2">
        <input type="text"
               class="form-control amount bg-light"
               value="{{ number_format($item->qty * $item->price,0,',','.') }}"
               readonly>
    </div>
    <div class="col-md-1 text-end">
        <button type="button"
                class="btn btn-sm btn-danger"
                onclick="removeItem(this)">
            X
        </button>
    </div>
</div>
@endforeach

</div>

<button type="button"
        onclick="addItem()"
        class="btn btn-sm btn-secondary mb-3">
+ Tambah Item
</button>

<hr>

{{-- DISCOUNT --}}
<div class="row">
<div class="col-md-3">
<select name="discount_type" class="form-control">
<option value="">No Discount</option>
<option value="percent"
    {{ $invoicePo->discount_type == 'percent' ? 'selected' : '' }}>
    Percent (%)
</option>
<option value="nominal"
    {{ $invoicePo->discount_type == 'nominal' ? 'selected' : '' }}>
    Nominal
</option>
</select>
</div>

<div class="col-md-3">
<input type="number" step="0.01"
       name="discount_value"
       value="{{ $invoicePo->discount_value }}"
       class="form-control"
       placeholder="Discount Value">
</div>
</div>

</div>
</div>

<button class="btn btn-success">
Update Invoice
</button>

</form>

</div>

{{-- SCRIPT --}}
<script>

let index = {{ $invoicePo->items->count() }};

function addItem(){
    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
        <div class="row mb-2 align-items-center item-row">
            <div class="col-md-3">
                <input type="text"
                       name="items[${index}][description]"
                       class="form-control"
                       placeholder="Description">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01"
                       name="items[${index}][qty]"
                       class="form-control qty"
                       placeholder="Qty">
            </div>
            <div class="col-md-2">
                <input type="text"
                       name="items[${index}][unit]"
                       class="form-control"
                       placeholder="Unit">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01"
                       name="items[${index}][price]"
                       class="form-control price"
                       placeholder="Price">
            </div>
            <div class="col-md-2">
                <input type="text"
                       class="form-control amount bg-light"
                       readonly>
            </div>
            <div class="col-md-1 text-end">
                <button type="button"
                        class="btn btn-sm btn-danger"
                        onclick="removeItem(this)">
                    X
                </button>
            </div>
        </div>
    `);

    index++;
    attachEvents();
}

function removeItem(button){
    button.closest('.item-row').remove();
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

        qty.addEventListener('input', calculate);
        price.addEventListener('input', calculate);
    });
}

attachEvents();

</script>

@endsection

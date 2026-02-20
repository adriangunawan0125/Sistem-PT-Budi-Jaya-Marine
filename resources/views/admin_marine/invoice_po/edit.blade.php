@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit Invoice</h4>

<form action="{{ route('invoice-po.update',$invoicePo->id) }}" method="POST">
@csrf
@method('PUT')

<div class="card shadow-sm mb-4">
<div class="card-body">

{{-- ================= HEADER ================= --}}
<div class="row g-3 mb-3">

    <div class="col-md-4">
        <label class="form-label small">No Invoice</label>
        <input type="text"
               name="no_invoice"
               value="{{ $invoicePo->no_invoice }}"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Tanggal</label>
        <input type="date"
               name="tanggal_invoice"
               value="{{ $invoicePo->tanggal_invoice }}"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Periode</label>
        <input type="text"
               name="periode"
               value="{{ $invoicePo->periode }}"
               class="form-control form-control-sm">
    </div>

</div>

<div class="row g-3 mb-3">

    <div class="col-md-6">
        <label class="form-label small">Authorization No</label>
        <input type="text"
               name="authorization_no"
               value="{{ $invoicePo->authorization_no }}"
               class="form-control form-control-sm">
    </div>

    <div class="col-md-6">
        <label class="form-label small">Manpower</label>
        <input type="text"
               name="manpower"
               value="{{ $invoicePo->manpower }}"
               class="form-control form-control-sm">
    </div>

</div>

<hr>

{{-- ================= ITEMS ================= --}}
<h6 class="mb-3">Item Invoice</h6>

<div id="items-wrapper">

@foreach($invoicePo->items as $i => $item)
<div class="row g-2 align-items-center mb-2 item-row">

    <div class="col-md-4">
        <textarea name="items[{{ $i }}][description]"
                  class="form-control form-control-sm"
                  rows="2">{{ $item->description }}</textarea>
    </div>

    <div class="col-md-2">
        <input type="number"
               step="0.01"
               name="items[{{ $i }}][qty]"
               value="{{ $item->qty }}"
               class="form-control form-control-sm qty">
    </div>

    <div class="col-md-2">
        <input type="text"
               name="items[{{ $i }}][unit]"
               value="{{ $item->unit }}"
               class="form-control form-control-sm">
    </div>

    <div class="col-md-2">
        <input type="number"
               step="0.01"
               name="items[{{ $i }}][price]"
               value="{{ $item->price }}"
               class="form-control form-control-sm price">
    </div>

    <div class="col-md-1">
        <input type="text"
               class="form-control form-control-sm amount bg-light"
               value="{{ $item->qty * $item->price }}"
               readonly>
    </div>

    <div class="col-md-1 text-end">
        <button type="button"
                class="btn btn-sm btn-danger"
                onclick="removeItem(this)">
            ×
        </button>
    </div>

</div>
@endforeach

</div>

<button type="button"
        onclick="addItem()"
        class="btn btn-sm btn-primary mt-2">
+ Tambah Item
</button>

<hr class="my-4">

{{-- ================= DISCOUNT ================= --}}
<div class="row g-3 align-items-end">

    <div class="col-md-3">
        <label class="form-label small">Discount Type</label>
        <select name="discount_type"
                class="form-control form-control-sm">
            <option value="">No Discount</option>
            <option value="percent"
                {{ $invoicePo->discount_type == 'percent' ? 'selected' : '' }}>
                Percent (%)
            </option>
            <option value="nominal"
                {{ $invoicePo->discount_type == 'nominal' ? 'selected' : '' }}>
                Nominal (Rp)
            </option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label small">Discount Value</label>
        <input type="number"
               step="0.01"
               name="discount_value"
               value="{{ $invoicePo->discount_value }}"
               class="form-control form-control-sm">
    </div>

</div>

</div>
</div>

<div class="d-flex justify-content gap-2 mt-4">

    <a href="{{ route('invoice-po.show', $invoicePo->id) }}"
       class="btn btn-secondary btn-sm px-4" style="margin-right: 4px">
        Kembali
    </a>

    <button type="submit"
            class="btn btn-success btn-sm px-4">
        Update Invoice
    </button>

</div>
</form>
</div>

{{-- ================= SCRIPT ================= --}}
<script>

let index = {{ $invoicePo->items->count() }};

function addItem(){

    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
        <div class="row g-2 align-items-center mb-2 item-row">

            <div class="col-md-4">
                <textarea name="items[${index}][description]"
                          class="form-control form-control-sm"
                          rows="2"
                          placeholder="Description"></textarea>
            </div>

            <div class="col-md-2">
                <input type="number"
                       step="0.01"
                       name="items[${index}][qty]"
                       class="form-control form-control-sm qty">
            </div>

            <div class="col-md-2">
                <input type="text"
                       name="items[${index}][unit]"
                       class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <input type="number"
                       step="0.01"
                       name="items[${index}][price]"
                       class="form-control form-control-sm price">
            </div>

            <div class="col-md-1">
                <input type="text"
                       class="form-control form-control-sm amount bg-light"
                       readonly>
            </div>

            <div class="col-md-1 text-end">
                <button type="button"
                        class="btn btn-sm btn-danger"
                        onclick="removeItem(this)">
                    ×
                </button>
            </div>

        </div>
    `);

    index++;
    attachEvents();
}

function removeItem(btn){
    btn.closest('.item-row').remove();
}

function attachEvents(){

    document.querySelectorAll('.item-row').forEach(row => {

        let qty = row.querySelector('.qty');
        let price = row.querySelector('.price');
        let amount = row.querySelector('.amount');

        function calculate(){
            let q = parseFloat(qty.value) || 0;
            let p = parseFloat(price.value) || 0;
            amount.value = (q * p);
        }

        qty.oninput = calculate;
        price.oninput = calculate;
    });
}

attachEvents();

</script>

@endsection

@extends('layouts.app')

@section('content')
<div class="container">

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit Delivery Order</h4>

    <a href="{{ route('delivery-order.show', $deliveryOrder->id) }}"
       class="btn btn-secondary btn-sm px-3">
        Kembali
    </a>
</div>

<form action="{{ route('delivery-order.update', $deliveryOrder->id) }}"
      method="POST">
@csrf
@method('PUT')

{{-- ================= INFO ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body px-4 py-4">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label small">No Delivery Order</label>
                <input type="text"
                       name="no_do"
                       value="{{ $deliveryOrder->no_do }}"
                       class="form-control form-control-sm"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label small">Tanggal DO</label>
                <input type="date"
                       name="tanggal_do"
                       value="{{ $deliveryOrder->tanggal_do }}"
                       class="form-control form-control-sm"
                       required>
            </div>

            <div class="col-md-4 mt-3">
                <label class="form-label small">Status</label>
                <select name="status"
                        class="form-control form-control-sm filter-control">
                    <option value="draft"
                        {{ $deliveryOrder->status=='draft'?'selected':'' }}>
                        Draft
                    </option>
                    <option value="delivered"
                        {{ $deliveryOrder->status=='delivered'?'selected':'' }}>
                        Delivered
                    </option>
                </select>
            </div>

        </div>

    </div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Items Delivery</strong>

        <button type="button"
                class="btn btn-primary btn-sm px-3"
                onclick="addItem()">
            + Add Item
        </button>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered mb-0 align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>Item</th>
                    <th width="120">Qty</th>
                    <th width="120">Unit</th>
                    <th width="80"></th>
                </tr>
            </thead>
            <tbody id="item-body">

                @foreach($deliveryOrder->items as $i => $item)
                <tr>
                    <td>
                        <input type="text"
                               name="items[{{ $i }}][item]"
                               value="{{ $item->item }}"
                               class="form-control form-control-sm"
                               required>
                    </td>

                    <td>
                        <input type="number"
                               step="0.01"
                               name="items[{{ $i }}][qty]"
                               value="{{ $item->qty }}"
                               class="form-control form-control-sm text-center"
                               required>
                    </td>

                    <td>
                        <input type="text"
                               name="items[{{ $i }}][unit]"
                               value="{{ $item->unit }}"
                               class="form-control form-control-sm text-center"
                               required>
                    </td>

                    <td class="text-center">
                        <button type="button"
                                class="btn btn-danger btn-sm"
                                onclick="removeRow(this)">
                            X
                        </button>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>

<div class="text-end">
    <button class="btn btn-success px-4">
        Update Delivery Order
    </button>
</div>

</form>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
let itemIndex = {{ $deliveryOrder->items->count() }};

function addItem(){

    let tbody = document.getElementById('item-body');

    let row = `
    <tr>
        <td>
            <input type="text"
                   name="items[${itemIndex}][item]"
                   class="form-control form-control-sm"
                   required>
        </td>

        <td>
            <input type="number"
                   step="0.01"
                   name="items[${itemIndex}][qty]"
                   class="form-control form-control-sm text-center"
                   required>
        </td>

        <td>
            <input type="text"
                   name="items[${itemIndex}][unit]"
                   class="form-control form-control-sm text-center"
                   required>
        </td>

        <td class="text-center">
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
}
</script>

@endsection

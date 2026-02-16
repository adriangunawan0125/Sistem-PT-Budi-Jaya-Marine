@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit Delivery Order</h4>

<form action="{{ route('delivery-order.update', $deliveryOrder->id) }}"
      method="POST">
@csrf
@method('PUT')

{{-- ================= INFO ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>No DO</label>
                <input type="text"
                       name="no_do"
                       value="{{ $deliveryOrder->no_do }}"
                       class="form-control"
                       required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Tanggal DO</label>
                <input type="date"
                       name="tanggal_do"
                       value="{{ $deliveryOrder->tanggal_do }}"
                       class="form-control"
                       required>
            </div>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
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

{{-- ================= ITEMS ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between">
        <strong>Items</strong>

        <button type="button"
                class="btn btn-sm btn-primary"
                onclick="addItem()">
            + Add Item
        </button>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
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
                               class="form-control"
                               required>
                    </td>

                    <td>
                        <input type="number"
                               step="0.01"
                               name="items[{{ $i }}][qty]"
                               value="{{ $item->qty }}"
                               class="form-control"
                               required>
                    </td>

                    <td>
                        <input type="text"
                               name="items[{{ $i }}][unit]"
                               value="{{ $item->unit }}"
                               class="form-control"
                               required>
                    </td>

                    <td>
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
    <button class="btn btn-success">
        Update Delivery Order
    </button>
</div>

</form>
</div>

<script>
let itemIndex = {{ $deliveryOrder->items->count() }};

function addItem(){

    let tbody = document.getElementById('item-body');

    let row = `
    <tr>
        <td>
            <input type="text"
                   name="items[${itemIndex}][item]"
                   class="form-control"
                   required>
        </td>

        <td>
            <input type="number"
                   step="0.01"
                   name="items[${itemIndex}][qty]"
                   class="form-control"
                   required>
        </td>

        <td>
            <input type="text"
                   name="items[${itemIndex}][unit]"
                   class="form-control"
                   required>
        </td>

        <td>
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

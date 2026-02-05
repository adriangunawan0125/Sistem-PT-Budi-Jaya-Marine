@extends('layouts.app')

@section('content')
<div class="container">

    <h3>Buat Delivery Order</h3>

    <div class="mb-3">
        <strong>PO Klien:</strong> {{ $poMasuk->no_po_klien }} <br>
        <strong>Perusahaan:</strong> {{ $poMasuk->nama_perusahaan }}
    </div>

    <form action="{{ route('delivery-order.store') }}" method="POST">
        @csrf

        <input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

        <div class="row mb-3">
            <div class="col-md-4">
                <label>No DO</label>
                <input type="text" name="no_do" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Tanggal DO</label>
                <input type="date" name="tanggal_do" class="form-control" required>
            </div>
        </div>

        <hr>

        <h5>Item yang Dikirim</h5>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Supplier</th>
                    <th>Item</th>
                    <th>Qty Kirim</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($poSuppliers as $supplier)
                    @foreach ($supplier->items as $item)
                        <tr>
                            <td>{{ $supplier->nama_perusahaan }}</td>
                            <td>{{ $item->item }}</td>
                            <td>
                                <input type="hidden"
                                       name="items[{{ $loop->parent->index }}{{ $loop->index }}][po_supplier_item_id]"
                                       value="{{ $item->id }}">

                                <input type="hidden"
                                       name="items[{{ $loop->parent->index }}{{ $loop->index }}][item]"
                                       value="{{ $item->item }}">

                                <input type="hidden"
                                       name="items[{{ $loop->parent->index }}{{ $loop->index }}][unit]"
                                       value="{{ $item->unit }}">

                                <input type="number"
                                       name="items[{{ $loop->parent->index }}{{ $loop->index }}][qty]"
                                       class="form-control"
                                       min="0"
                                       step="0.01"
                                       placeholder="Qty kirim">
                            </td>
                            <td>{{ $item->unit }}</td>
                        </tr>
                    @endforeach
                @endforeach

            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">
            Simpan Delivery Order
        </button>

        <a href="{{ url('/delivery-order/'.$poMasuk->id) }}"
           class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>
@endsection

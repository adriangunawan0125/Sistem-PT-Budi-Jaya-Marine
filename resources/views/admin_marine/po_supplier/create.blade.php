@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Buat PO Supplier</h4>

    <form action="{{ route('po-supplier.store') }}" method="POST">
        @csrf

        <input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

        {{-- INFO SUPPLIER --}}
        <div class="card mb-4">
            <div class="card-body">
                <input name="nama_perusahaan"
                       class="form-control mb-2"
                       placeholder="Nama perusahaan"
                       required>

                <textarea name="alamat"
                          class="form-control mb-2"
                          placeholder="Alamat"></textarea>

                <input type="date"
                       name="tanggal_po"
                       class="form-control mb-2"
                       required>

                <input name="no_po_internal"
                       class="form-control mb-2"
                       placeholder="No PO internal"
                       required>
            </div>
        </div>

        {{-- ITEM PO KLIEN --}}
        <div class="card">
            <div class="card-header fw-bold">
                Pilih Item dari PO Klien
            </div>

            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Pilih</th>
                            <th>Item</th>
                            <th>Qty PO</th>
                            <th>Qty Beli</th>
                            <th>Unit</th>
                            <th>Harga Beli</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($poMasuk->items as $i => $item)
                        <tr>
                            <td>
                                <input type="checkbox"
                                    name="items[{{ $i }}][po_masuk_item_id]"
                                    value="{{ $item->id }}">
                            </td>

                            <td>{{ $item->item }}</td>
                            <td>{{ $item->qty }}</td>

                            <td>
                                <input type="number"
                                    name="items[{{ $i }}][qty]"
                                    class="form-control"
                                    step="0.01"
                                    max="{{ $item->qty }}">
                            </td>

                            <td>{{ $item->unit }}</td>

                            <td>
                                <input type="number"
                                    name="items[{{ $i }}][price_beli]"
                                    class="form-control"
                                    step="0.01">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary">
                Simpan PO Supplier
            </button>
        </div>
    </form>
</div>
@endsection

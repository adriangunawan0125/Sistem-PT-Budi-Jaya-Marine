@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah PO Masuk</h4>

    <form action="{{ route('po-masuk.store') }}" method="POST">
        @csrf

        {{-- HEADER PO --}}
        <div class="card mb-3">
            <div class="card-header">Data PO</div>
            <div class="card-body">
                <div class="mb-2">
                    <label>Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>

                <div class="mb-2">
                    <label>Tanggal PO</label>
                    <input type="date" name="tanggal_po" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label>No PO Klien</label>
                    <input type="text" name="no_po_klien" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label>Vessel</label>
                    <input type="text" name="vessel" class="form-control" required>
                </div>
            </div>
        </div>

        {{-- ITEM PO --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <span>Item PO</span>
                <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                    + Tambah Item
                </button>
            </div>

            <div class="card-body">
                <table class="table" id="itemTable">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="items[0][item]" class="form-control" required></td>
                            <td><input type="number" name="items[0][qty]" class="form-control qty" required></td>
                            <td><input type="text" name="items[0][unit]" class="form-control" required></td>
                            <td><input type="number" name="items[0][price]" class="form-control price" required></td>
                            <td><input type="number" name="items[0][amount]" class="form-control amount" readonly></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <button class="btn btn-primary">Simpan PO</button>
    </form>
</div>

<script>
let index = 1;

function addRow() {
    let row = `
    <tr>
        <td><input type="text" name="items[${index}][item]" class="form-control" required></td>
        <td><input type="number" name="items[${index}][qty]" class="form-control qty" required></td>
        <td><input type="text" name="items[${index}][unit]" class="form-control" required></td>
        <td><input type="number" name="items[${index}][price]" class="form-control price" required></td>
        <td><input type="number" name="items[${index}][amount]" class="form-control amount" readonly></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">X</button>
        </td>
    </tr>`;
    document.querySelector('#itemTable tbody').insertAdjacentHTML('beforeend', row);
    index++;
}

document.addEventListener('input', function (e) {
    if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
        let row = e.target.closest('tr');
        let qty = row.querySelector('.qty').value || 0;
        let price = row.querySelector('.price').value || 0;
        row.querySelector('.amount').value = qty * price;
    }
});
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Pengeluaran Transport</h4>

    <form method="POST" action="{{ route('pengeluaran_transport.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Plat Nomor</label>
            <select name="unit_id" class="form-control" required>
                <option value="">Pilih Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <h5>Item Pengeluaran</h5>
        <table class="table table-bordered" id="items_table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="keterangan[]" class="form-control" required></td>
                    <td><input type="number" name="nominal[]" class="form-control" required></td>
                    <td><input type="file" name="gambar[]" class="form-control" accept="image/*"></td>
                    <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-secondary mb-3" id="add_item">Tambah Item</button>
        <br>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script>
document.getElementById('add_item').addEventListener('click', function(){
    let table = document.getElementById('items_table').getElementsByTagName('tbody')[0];
    let newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="text" name="keterangan[]" class="form-control" required></td>
        <td><input type="number" name="nominal[]" class="form-control" required></td>
        <td><input type="file" name="gambar[]" class="form-control" accept="image/*"></td>
        <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
    `;
    table.appendChild(newRow);
});

// Hapus row
document.addEventListener('click', function(e){
    if(e.target && e.target.classList.contains('remove-row')){
        e.target.closest('tr').remove();
    }
});
</script>
@endsection

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
                    <td>
                        <input type="text" name="keterangan[]" class="form-control" required>
                    </td>

                    <td>
                        <input type="text"
                               class="form-control rupiah"
                               placeholder="Rp 0"
                               required>
                        <input type="hidden" name="nominal[]" value="0">
                    </td>

                    <td>
                        <input type="file" name="gambar[]" class="form-control" accept="image/*">
                    </td>

                    <td>
                        <button type="button" class="btn btn-danger remove-row">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-secondary mb-3" id="add_item">Tambah Item</button>
        <br>
        <button type="submit" class="btn btn-primary">Simpan</button>
         <a href="{{ route('pengeluaran_transport.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>

<script>
// ================= FORMAT RUPIAH =================
function formatRupiah(angka) {
    let number_string = angka.replace(/\D/g, ''),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return rupiah ? 'Rp ' + rupiah : '';
}

function bindRupiah(input) {
    input.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');
        raw = raw.replace(/^0+/, '');

        this.value = raw ? formatRupiah(raw) : '';
        this.nextElementSibling.value = raw || 0;
    });
}

document.querySelectorAll('.rupiah').forEach(bindRupiah);

// ================= TAMBAH ROW =================
document.getElementById('add_item').addEventListener('click', function () {
    let tbody = document.querySelector('#items_table tbody');
    let row = document.createElement('tr');

    row.innerHTML = `
        <td><input type="text" name="keterangan[]" class="form-control" required></td>
        <td>
            <input type="text" class="form-control rupiah" placeholder="Rp 0" required>
            <input type="hidden" name="nominal[]" value="0">
        </td>
        <td><input type="file" name="gambar[]" class="form-control" accept="image/*"></td>
        <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
    `;

    tbody.appendChild(row);
    bindRupiah(row.querySelector('.rupiah'));
});

// ================= HAPUS ROW =================
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endsection

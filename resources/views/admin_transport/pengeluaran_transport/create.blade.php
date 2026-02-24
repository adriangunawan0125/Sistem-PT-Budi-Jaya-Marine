@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Pengeluaran Transport</h4>
 
    @if ($errors->any())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form id="formUnit"
          method="POST"
          action="{{ route('pengeluaran_transport.store') }}"
          enctype="multipart/form-data">
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
                    <th>Gambar Nota</th>
                    <th>Bukti TF</th>
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
                        <input type="file"
                               name="gambar[]"
                               class="form-control preview-input"
                               accept="image/*">
                        <img class="img-preview mt-2"
                             style="max-height:90px; display:none; border:1px solid #ddd; border-radius:6px;">
                    </td>

                    <td>
                        <input type="file"
                               name="gambar1[]"
                               class="form-control preview-input"
                               accept="image/*">
                        <img class="img-preview mt-2"
                             style="max-height:90px; display:none; border:1px solid #ddd; border-radius:6px;">
                    </td>

                    <td>
                        <button type="button" class="btn btn-danger remove-row">
                            Hapus
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button"
                class="btn btn-secondary mb-3"
                id="add_item">
            Tambah Item
        </button>

        <br>

        <button type="submit"
                class="btn btn-primary"
                onclick="return cekItem()">
            Simpan
        </button>

        <a href="{{ route('pengeluaran_transport.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>

<script>
function cekItem() {
    let rows = document.querySelectorAll('#items_table tbody tr');
    if (rows.length === 0) {
        alert('Minimal harus ada 1 item pengeluaran');
        return false;
    }

    let valid = true;
    document.querySelectorAll('input[name="nominal[]"]').forEach(el => {
        if (!el.value || el.value == 0) {
            valid = false;
        }
    });

    if (!valid) {
        alert('Nominal tidak boleh kosong');
        return false;
    }

    return true;
}
</script>

<style>
#loadingModal .modal-content{
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    height:120px;
}
</style>

<script>
/* ================= FORMAT RUPIAH ================= */
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

/* ================= PREVIEW IMAGE ================= */
function bindPreview(row){

    row.querySelectorAll('.preview-input').forEach(input => {

        input.addEventListener('change', function(){

            const preview = this.nextElementSibling;

            if(this.files && this.files[0]){
                const reader = new FileReader();

                reader.onload = function(e){
                    preview.src = e.target.result;
                    preview.style.display = "block";
                }

                reader.readAsDataURL(this.files[0]);

            } else {
                preview.src = "";
                preview.style.display = "none";
            }
        });

    });
}

bindPreview(document.querySelector('#items_table tbody tr'));

/* ================= TAMBAH ROW ================= */
document.getElementById('add_item').addEventListener('click', function () {

    let tbody = document.querySelector('#items_table tbody');
    let row = document.createElement('tr');

    row.innerHTML = `
        <td><input type="text" name="keterangan[]" class="form-control" required></td>
        <td>
            <input type="text" class="form-control rupiah" placeholder="Rp 0" required>
            <input type="hidden" name="nominal[]" value="0">
        </td>
        <td>
            <input type="file" name="gambar[]" class="form-control preview-input" accept="image/*">
            <img class="img-preview mt-2"
                 style="max-height:90px; display:none; border:1px solid #ddd; border-radius:6px;">
        </td>
        <td>
            <input type="file" name="gambar1[]" class="form-control preview-input" accept="image/*">
            <img class="img-preview mt-2"
                 style="max-height:90px; display:none; border:1px solid #ddd; border-radius:6px;">
        </td>
        <td>
            <button type="button" class="btn btn-danger remove-row">Hapus</button>
        </td>
    `;

    tbody.appendChild(row);

    bindRupiah(row.querySelector('.rupiah'));
    bindPreview(row);
});

/* ================= HAPUS ROW ================= */
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});
</script>

@endsection
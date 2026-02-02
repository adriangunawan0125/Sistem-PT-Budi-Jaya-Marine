@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Pengeluaran Pajak</h4>

    <form method="POST"
          action="{{ route('pengeluaran_pajak.store') }}"
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
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Nominal</label>
            <input type="text"
                   class="form-control rupiah"
                   placeholder="Rp 0"
                   required>
            <input type="hidden"
                   name="nominal"
                   value="0">
        </div>

        <div class="mb-3">
            <label>Gambar</label>
            <input type="file"
                   name="gambar"
                   class="form-control"
                   accept="image/*">
            <small class="text-muted">Opsional</small>
        </div>

        <button type="submit" class="btn btn-primary">
            Simpan
        </button>
        <a href="{{ route('pengeluaran_pajak.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>

<script>
// ================= RUPIAH =================
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

function bindRupiah(el) {
    el.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');
        raw = raw.replace(/^0+/, '');

        this.value = raw ? formatRupiah(raw) : '';
        this.nextElementSibling.value = raw || 0;
    });
}

document.querySelectorAll('.rupiah').forEach(bindRupiah);
</script>
@endsection

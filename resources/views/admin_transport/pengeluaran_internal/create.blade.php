@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Pengeluaran Internal</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengeluaran_internal.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   placeholder="Contoh: Beli ATK"
                   required>
        </div>

        {{-- NOMINAL (SAMA KAYAK INVOICE & PEMASUKAN STYLE) --}}
        <div class="mb-3">
            <label class="form-label">Nominal</label>

            <input type="text"
                   class="form-control rupiah"
                   data-hidden="nominal"
                   placeholder="Rp 0">

            <input type="hidden"
                   name="nominal"
                   value="0">
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar (Bukti)</label>
            <input type="file"
                   name="gambar"
                   class="form-control"
                   accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">
            Simpan
        </button>

        <a href="{{ route('pengeluaran_internal.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>

{{-- ================= JS RUPIAH (FIX 02 / NORMAL NGETIK) ================= --}}
<script>
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

document.querySelectorAll('.rupiah').forEach(el => {
    el.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');
        raw = raw.replace(/^0+/, '');

        this.value = raw ? formatRupiah(raw) : '';

        let hiddenName = this.dataset.hidden;
        document.querySelector(`input[name="${hiddenName}"]`).value = raw || 0;
    });
});
</script>
@endsection

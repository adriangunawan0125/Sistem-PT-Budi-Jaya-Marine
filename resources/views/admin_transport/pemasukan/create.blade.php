@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Pemasukan Transport</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="createForm"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ old('tanggal') }}"
                   required>
        </div>

        <div class="mb-3">
    <label class="form-label">Nama Mitra</label>
    <select name="mitra_id" class="form-control" required>
        <option value="">-- Pilih Mitra --</option>
        @foreach($mitras as $mitra)
            <option value="{{ $mitra->id }}"
                {{ old('mitra_id') == $mitra->id ? 'selected' : '' }}>
                {{ $mitra->nama_mitra }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Kategori</label>
    <select name="kategori" class="form-control" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="setoran">Setoran</option>
        <option value="cicilan">Cicilan</option>
        <option value="deposit">Deposit</option>
    </select>
</div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ old('deskripsi') }}"
                   required>
        </div>

       <div class="mb-3">
    <label class="form-label">Nominal</label>

    <input type="text"
           class="form-control rupiah"
           data-hidden="nominal"
           placeholder="Rp 0"
           value="{{ old('nominal') ? 'Rp ' . number_format(old('nominal'), 0, ',', '.') : '' }}">

    <input type="hidden"
           name="nominal"
           value="{{ old('nominal', 0) }}">
</div>


        <div class="mb-3">
            <label class="form-label">Gambar (Bukti)</label>
            <input type="file"
                   name="gambar"
                   class="form-control"
                   accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">
            Simpan
        </button>

        <a href="{{ route('pemasukan.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>
<!-- LOADING MODAL -->
<div class="modal fade"
     id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3"
                     style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Menyimpan data...</div>
            </div>
        </div>
    </div>
</div>

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
<script>
function showLoading() {

    const modalEl = document.getElementById('loadingModal');
    const modal = new bootstrap.Modal(modalEl);

    modal.show();
}
</script>
@endsection

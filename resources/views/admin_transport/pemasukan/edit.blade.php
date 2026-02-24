@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Edit Pemasukan Transport</h4>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editForm"
          action="{{ route('pemasukan.update', $pemasukan->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- MITRA --}}
        <div class="mb-3">
            <label class="form-label">Nama Mitra</label>
            <input type="text"
                   class="form-control"
                   value="{{ $pemasukan->mitra->nama_mitra ?? '-' }}"
                   disabled>
        </div>

        {{-- TANGGAL --}}
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ old('tanggal', \Carbon\Carbon::parse($pemasukan->tanggal)->format('Y-m-d')) }}"
                   required>
        </div>

        {{-- KATEGORI --}}
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="setoran" {{ old('kategori', $pemasukan->kategori)=='setoran'?'selected':'' }}>Setoran</option>
                <option value="cicilan" {{ old('kategori', $pemasukan->kategori)=='cicilan'?'selected':'' }}>Cicilan</option>
                <option value="deposit" {{ old('kategori', $pemasukan->kategori)=='deposit'?'selected':'' }}>Deposit</option>
            </select>
        </div>

        {{-- DESKRIPSI --}}
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ old('deskripsi', $pemasukan->deskripsi) }}"
                   required>
        </div>

        {{-- NOMINAL --}}
        <div class="mb-3">
            <label class="form-label">Nominal</label>

            <input type="text"
                   class="form-control rupiah"
                   data-hidden="nominal"
                   placeholder="Rp 0"
                   value="Rp {{ number_format(old('nominal', $pemasukan->nominal), 0, ',', '.') }}">

            <input type="hidden"
                   name="nominal"
                   value="{{ old('nominal', $pemasukan->nominal) }}">
        </div>

        {{-- ================= BUKTI TF 1 ================= --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Bukti TF 1</label>

            <input type="file"
                   name="gambar"
                   id="input-gambar"
                   class="form-control"
                   accept="image/*">

            {{-- Preview realtime --}}
            <img id="preview-gambar"
                 class="img-thumbnail mt-2"
                 style="max-width:180px; display:none;">

            {{-- Gambar lama --}}
            @if ($pemasukan->gambar)
                <div class="mt-3">
                    <small class="text-muted">Gambar saat ini:</small><br>
                    <img src="{{ asset('storage/pemasukan/'.$pemasukan->gambar) }}"
                         style="max-width:180px;"
                         class="img-thumbnail">
                </div>
            @endif
        </div>

        {{-- ================= BUKTI TF 2 ================= --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">
                Bukti TF 2 (Jika transfer 2 kali)
            </label>

            <input type="file"
                   name="gambar1"
                   id="input-gambar1"
                   class="form-control"
                   accept="image/*">

            {{-- Preview realtime --}}
            <img id="preview-gambar1"
                 class="img-thumbnail mt-2"
                 style="max-width:180px; display:none;">

            {{-- Gambar lama --}}
            @if ($pemasukan->gambar1)
                <div class="mt-3">
                    <small class="text-muted">Bukti TF saat ini:</small><br>
                    <img src="{{ asset('storage/pemasukan/'.$pemasukan->gambar1) }}"
                         style="max-width:180px;"
                         class="img-thumbnail">
                </div>
            @endif
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                Update
            </button>

            <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>


<!-- LOADING MODAL -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Memperbarui data...</div>
            </div>
        </div>
    </div>
</div>


{{-- ================= RUPIAH FORMAT ================= --}}
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
        document.querySelector('input[name="'+this.dataset.hidden+'"]').value = raw || 0;
    });
});
</script>


{{-- ================= PREVIEW REALTIME ================= --}}
<script>
function bindPreview(inputId, previewId) {

    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (!input) return;

    input.addEventListener("change", function(){

        if (this.files.length > 0) {

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
}

document.addEventListener("DOMContentLoaded", function(){

    bindPreview("input-gambar", "preview-gambar");
    bindPreview("input-gambar1", "preview-gambar1");

    const form = document.getElementById('editForm');
    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        modal.show();
        setTimeout(() => {
            form.submit();
        }, 200);
    });
});
</script>

@endsection
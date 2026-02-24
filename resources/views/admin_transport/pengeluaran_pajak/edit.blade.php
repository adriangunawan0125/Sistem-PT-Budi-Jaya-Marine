@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Pengeluaran Pajak</h4>

    @if ($errors->any())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <input type="hidden" id="success-message" value="{{ session('success') }}">
    @endif

    <form id="editForm"
          method="POST"
          action="{{ route('pengeluaran_pajak.update', $pengeluaranPajak->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Plat Nomor</label>
            <select name="unit_id" class="form-control" required>
                <option value="">Pilih Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}"
                        {{ $pengeluaranPajak->unit_id == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nama_unit }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ $pengeluaranPajak->tanggal }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ $pengeluaranPajak->deskripsi }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Nominal</label>
            <input type="text"
                   class="form-control rupiah"
                   value="Rp {{ number_format($pengeluaranPajak->nominal, 0, ',', '.') }}"
                   required>
            <input type="hidden"
                   name="nominal"
                   value="{{ $pengeluaranPajak->nominal }}">
        </div>

        {{-- ================= Gambar Nota ================= --}}
        <div class="mb-3">
            <label>Gambar Nota Pajak</label>

            <img id="preview_gambar"
                 src="{{ $pengeluaranPajak->gambar ? asset('storage/'.$pengeluaranPajak->gambar) : '' }}"
                 style="max-height:120px;
                        {{ $pengeluaranPajak->gambar ? '' : 'display:none;' }}
                        border:1px solid #ddd;
                        border-radius:6px;
                        margin-bottom:10px;">

            <input type="file"
                   name="gambar"
                   id="gambar"
                   class="form-control"
                   accept="image/*">

            <small class="text-muted">
                Opsional, pilih jika ingin mengganti gambar
            </small>
        </div>

        {{-- ================= Bukti TF ================= --}}
        <div class="mb-3">
            <label>Bukti Transfer / Pembayaran</label>

            <img id="preview_gambar1"
                 src="{{ $pengeluaranPajak->gambar1 ? asset('storage/'.$pengeluaranPajak->gambar1) : '' }}"
                 style="max-height:120px;
                        {{ $pengeluaranPajak->gambar1 ? '' : 'display:none;' }}
                        border:1px solid #ddd;
                        border-radius:6px;
                        margin-bottom:10px;">

            <input type="file"
                   name="gambar1"
                   id="gambar1"
                   class="form-control"
                   accept="image/*">

            <small class="text-muted">
                Opsional, pilih jika ingin mengganti bukti transfer
            </small>
        </div>

        <button type="submit" class="btn btn-primary">
            Update
        </button>

        <a href="{{ route('pengeluaran_pajak.index') }}"
           class="btn btn-secondary">
            Batal
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
                <div class="fw-semibold">Memperbarui data...</div>
            </div>
        </div>
    </div>
</div>

<!-- SUCCESS MODAL -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size:60px;"></i>
                </div>
                <h5 class="fw-bold mb-2">Berhasil</h5>
                <div id="successText" class="text-muted"></div>
                <div class="mt-4">
                    <button class="btn btn-success px-4" data-bs-dismiss="modal">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
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

// ================= PREVIEW IMAGE =================
function bindPreview(inputId, previewId) {

    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    input.addEventListener('change', function(){

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
}

bindPreview('gambar', 'preview_gambar');
bindPreview('gambar1', 'preview_gambar1');

</script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("editForm");
    const loadingModal = new bootstrap.Modal(document.getElementById("loadingModal"));

    form.addEventListener("submit", function(e){

        e.preventDefault();

        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        loadingModal.show();

        setTimeout(() => {
            form.submit();
        }, 150);
    });

    const successInput = document.getElementById("success-message");

    if(successInput){
        const modal = new bootstrap.Modal(document.getElementById("successModal"));
        document.getElementById("successText").innerText = successInput.value;

        setTimeout(() => {
            modal.show();
        }, 250);
    }

});
</script>

@endsection
@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Tambah Pengeluaran PO</h4>

        <a href="{{ route('po-masuk.show',$poMasuk->id) }}"
           class="btn btn-secondary btn-sm px-3">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body px-4 py-4">

            {{-- ERROR ALERT --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('pengeluaran-po.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data" id="createPengeluaranForm">
                @csrf

                <input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

                <div class="row g-3">

                    {{-- PO CLIENT --}}
                    <div class="col-md-6">
                        <label class="form-label small">PO Klien</label>
                        <input type="text"
                               class="form-control form-control-sm bg-light"
                               value="{{ $poMasuk->no_po_klien }}"
                               readonly>
                    </div>

                    {{-- NAMA PENGELUARAN --}}
                    <div class="col-md-6">
                        <label class="form-label small">Nama Pengeluaran</label>
                        <input type="text"
                               name="item"
                               value="{{ old('item') }}"
                               class="form-control form-control-sm"
                               required>
                    </div>

                    {{-- QTY --}}
                    <div class="col-md-6">
                        <label class="form-label small">Qty</label>
                        <input type="number"
                               step="0.01"
                               name="qty"
                               value="{{ old('qty') }}"
                               class="form-control form-control-sm text-center"
                               required>
                    </div>

                 {{-- HARGA --}}
<div class="col-md-6">
    <label class="form-label small">Harga</label>

    <input type="text"
           class="form-control form-control-sm text-end rupiah"
           data-hidden="price_hidden"
           placeholder="Rp 0"
           value="{{ old('price') ? 'Rp ' . number_format(old('price'),0,',','.') : '' }}"
           required>

    <input type="hidden"
           name="price"
           id="price_hidden"
           value="{{ old('price',0) }}">
</div>

                    {{-- BUKTI GAMBAR --}}
                    <div class="col-md-12">
                        <label class="form-label small">Bukti Pengeluaran (jpg/png)</label>
                        <input type="file"
                               name="bukti_gambar"
                               class="form-control form-control-sm"
                               accept="image/*"
                               onchange="previewImage(event)">

                        {{-- PREVIEW --}}
                        <div class="mt-3">
                            <img id="preview"
                                 style="max-height:200px; display:none;"
                                 class="img-thumbnail">
                        </div>
                    </div>

                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-primary px-4">
                        Simpan Pengeluaran
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
{{-- LOADING MODAL --}}
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

<div class="fw-semibold">
Menyimpan Pengeluaran...
</div>

</div>
</div>
</div>
</div>

{{-- PREVIEW SCRIPT --}}
<script>
function previewImage(event){
    const input = event.target;
    const preview = document.getElementById('preview');

    if(input.files && input.files[0]){
        const reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("createPengeluaranForm");

    if(!form) return;

    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    form.addEventListener("submit", function(e){

        e.preventDefault();

        // Validasi bawaan HTML
        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        loadingModal.show();

        setTimeout(function(){
            HTMLFormElement.prototype.submit.call(form);
        }, 250);

    });

});
</script>
<script>

function formatRupiah(angka){
    let number_string = angka.replace(/\D/g,''),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if(ribuan){
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return rupiah ? 'Rp ' + rupiah : '';
}

document.querySelectorAll('.rupiah').forEach(el => {

    el.addEventListener('input', function(){

        let raw = this.value.replace(/\D/g,'');
        raw = raw.replace(/^0+/,'');

        this.value = raw ? formatRupiah(raw) : '';

        let hiddenId = this.dataset.hidden;
        document.getElementById(hiddenId).value = raw || 0;
    });

});

</script>
@endsection
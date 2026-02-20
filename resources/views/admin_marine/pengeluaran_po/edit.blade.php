@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Edit Pengeluaran</h4>

        <a href="{{ route('po-masuk.show',$pengeluaranPo->po_masuk_id) }}"
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

            <form action="{{ route('pengeluaran-po.update',$pengeluaranPo->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- NAMA PENGELUARAN --}}
                    <div class="col-md-12">
                        <label class="form-label small">Nama Pengeluaran</label>
                        <input type="text"
                               name="item"
                               value="{{ old('item', $pengeluaranPo->item) }}"
                               class="form-control form-control-sm"
                               required>
                    </div>

                    {{-- QTY --}}
                    <div class="col-md-6">
                        <label class="form-label small">Qty</label>
                        <input type="number"
                               step="0.01"
                               name="qty"
                               value="{{ old('qty', $pengeluaranPo->qty) }}"
                               class="form-control form-control-sm text-center"
                               required>
                    </div>

                    {{-- HARGA --}}
                    <div class="col-md-6">
                        <label class="form-label small">Harga</label>
                        <input type="number"
                               step="0.01"
                               name="price"
                               value="{{ old('price', $pengeluaranPo->price) }}"
                               class="form-control form-control-sm text-end"
                               required>
                    </div>

                    {{-- GAMBAR LAMA --}}
                    @if($pengeluaranPo->bukti_gambar)
                        <div class="col-md-12">
                            <label class="form-label small">Bukti Saat Ini</label>
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$pengeluaranPo->bukti_gambar) }}"
                                     class="img-thumbnail"
                                     style="max-height:200px;">
                            </div>
                        </div>
                    @endif

                    {{-- UPLOAD GAMBAR BARU --}}
                    <div class="col-md-12">
                        <label class="form-label small">
                            Ganti Bukti Pengeluaran (opsional)
                        </label>
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
                    <button class="btn btn-warning px-4">
                        Update Pengeluaran
                    </button>
                </div>

            </form>

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

@endsection
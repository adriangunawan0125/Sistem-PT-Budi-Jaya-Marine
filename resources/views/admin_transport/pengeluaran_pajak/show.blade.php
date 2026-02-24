@extends('layouts.app')

@section('content')
<style>
.detail-wrapper{
    background:#fff;
    border-radius:10px;
}

.detail-row{
    display:flex;
    margin-bottom:10px;
}

.detail-label{
    width:170px;
    font-weight:600;
}

.detail-value{
    flex:1;
}

.preview-img{
    max-height:300px;
    border-radius:8px;
    border:1px solid #dee2e6;
    cursor:pointer;
}
</style>

<div class="container">
<div class="detail-wrapper p-4 shadow-sm">

<h4 class="mb-4">Detail Pengeluaran Pajak Mobil</h4>

<div class="detail-row">
    <div class="detail-label">Plat Nomor</div>
    <div class="detail-value">
        : {{ $pajak->unit->nama_unit }}
    </div>
</div>

<div class="detail-row">
    <div class="detail-label">Tanggal</div>
    <div class="detail-value">
        : {{ \Carbon\Carbon::parse($pajak->tanggal)->format('d-m-Y') }}
    </div>
</div>

<div class="detail-row">
    <div class="detail-label">Deskripsi</div>
    <div class="detail-value">
        : {{ $pajak->deskripsi }}
    </div>
</div>

<div class="detail-row">
    <div class="detail-label">Nominal</div>
    <div class="detail-value fw-bold">
        : Rp {{ number_format($pajak->nominal,0,',','.') }}
    </div>
</div>

<div class="detail-row">
    <div class="detail-label">Gambar</div>
    <div class="detail-value">
        :
        @if($pajak->gambar)
            <div class="mt-2">
                <img src="{{ asset('storage/'.$pajak->gambar) }}"
                     class="preview-img"
                     data-bs-toggle="modal"
                     data-bs-target="#imageModal"
                     onclick="showImage(this.src)">
            </div>
        @else
            <span class="text-muted">Tidak ada gambar</span>
        @endif
    </div>
</div>

<hr class="my-4">

<a href="{{ route('pengeluaran_pajak.index') }}"
   class="btn btn-secondary btn-sm">
    Kembali
</a>

</div>
</div>

{{-- IMAGE MODAL --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark text-center">

            <div class="modal-header border-0">
                <button type="button"
                        class="btn-close btn-close-white ms-auto"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body d-flex align-items-center justify-content-center">
                <img id="modalImage"
                     src=""
                     class="img-fluid"
                     style="max-height:95vh;">
            </div>

        </div>
    </div>
</div>

<script>
function showImage(src){
    document.getElementById('modalImage').src = src;
}
</script>

@endsection
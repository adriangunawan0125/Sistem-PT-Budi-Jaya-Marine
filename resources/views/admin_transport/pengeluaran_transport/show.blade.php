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

.item-table th{
    font-size:13px;
    background:#f8f9fa;
}

.item-table td{
    font-size:13px;
}

.preview-img{
    max-height:250px;
    border-radius:8px;
    border:1px solid #dee2e6;
    cursor:pointer;
}
</style>

<div class="container">
<div class="detail-wrapper p-4 shadow-sm">

<h4 class="mb-4">Detail Pengeluaran Transport</h4>

{{-- HEADER INFO --}}
<div class="detail-row">
    <div class="detail-label">Unit</div>
    <div class="detail-value">
        : {{ $pengeluaran_transport->unit->nama_unit }}
    </div>
</div>

<div class="detail-row">
    <div class="detail-label">Tanggal</div>
    <div class="detail-value">
        : {{ \Carbon\Carbon::parse($pengeluaran_transport->tanggal)->format('d-m-Y') }}
    </div>
</div>

<div class="detail-row">
    <div class="detail-label">Total</div>
    <div class="detail-value fw-bold">
        : Rp {{ number_format($pengeluaran_transport->total_amount,0,',','.') }}
    </div>
</div>

<hr class="my-4">

<h5 class="mb-3">Detail Item</h5>

<table class="table table-bordered item-table">
    <thead>
        <tr>
            <th width="50">No</th>
            <th>Keterangan</th>
            <th width="150">Nominal</th>
            <th width="150">Gambar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengeluaran_transport->items as $index => $item)
        <tr>
            <td class="text-center">{{ $index+1 }}</td>
            <td>{{ $item->keterangan }}</td>
            <td class="text-end">
                Rp {{ number_format($item->nominal,0,',','.') }}
            </td>
            <td class="text-center">
                @if($item->gambar)
                    <img src="{{ asset('storage/'.$item->gambar) }}"
                         class="preview-img"
                         data-bs-toggle="modal"
                         data-bs-target="#imageModal"
                         onclick="showImage(this.src)">
                @else
                    -
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('pengeluaran_transport.index') }}"
   class="btn btn-secondary btn-sm mt-3">
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
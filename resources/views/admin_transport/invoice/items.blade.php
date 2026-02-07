@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Detail Item Invoice</h4>

    {{-- INFO INVOICE --}}
    <div class="mb-4">

        <div class="d-flex"><div style="width:170px"><b>Mitra</b></div><div>: {{ $invoice->mitra->nama_mitra ?? '-' }}</div></div>

        @if($invoice->exMitra)
        <div class="d-flex"><div style="width:170px"><b>Ex Mitra</b></div><div>: {{ $invoice->exMitra->nama_mitra }}</div></div>
        @endif

        <div class="d-flex">
            <div style="width:170px"><b>Status</b></div>
            <div>:
                <span class="badge text-light bg-{{ $invoice->status == 'lunas' ? 'success' : 'danger' }}">
                    {{ strtoupper($invoice->status) }}
                </span>
            </div>
        </div>

        <div class="d-flex"><div style="width:170px"><b>Total</b></div><div>: Rp {{ number_format($invoice->total,0,',','.') }}</div></div>
       
    </div>


    {{-- LIST ITEM --}}
    @forelse($invoice->items as $i => $item)

        <hr class="my-4">

        <h5 class="mb-3">Item </h5>

        <div class="d-flex mb-1"><div style="width:170px"><b>No Invoice</b></div><div>: {{ $item->no_invoices ?? '-' }}</div></div>

        <div class="d-flex mb-1">
            <div style="width:170px"><b>Tanggal Invoice</b></div>
            <div>: {{ $item->tanggal_invoices ? \Carbon\Carbon::parse($item->tanggal_invoices)->format('d-m-Y') : '-' }}</div>
        </div>

        <div class="d-flex mb-1"><div style="width:170px"><b>Item</b></div><div>: {{ $item->item }}</div></div>

        <div class="d-flex mb-1">
            <div style="width:170px"><b>Tanggal TF</b></div>
            <div>: {{ $item->tanggal_tf ? \Carbon\Carbon::parse($item->tanggal_tf)->format('d-m-Y') : '-' }}</div>
        </div>

        <div class="d-flex mb-1"><div style="width:170px"><b>Cicilan</b></div><div>: Rp {{ number_format($item->cicilan,0,',','.') }}</div></div>
        <div class="d-flex mb-1"><div style="width:170px"><b>Tagihan</b></div><div>: Rp {{ number_format($item->tagihan,0,',','.') }}</div></div>

        <div class="d-flex mb-3">
            <div style="width:170px"><b>Amount</b></div>
            <div>: <span class="text-success fw-bold">Rp {{ number_format($item->amount,0,',','.') }}</span></div>
        </div>


        {{-- GAMBAR TRANSFER --}}
        <div class="mb-4">
            <b>Bukti Transfer</b><br>

            @if($item->gambar_transfer)
                <img src="{{ asset('storage/'.$item->gambar_transfer) }}"
                     class="img-fluid border mt-2 preview-image"
                     style="cursor:pointer; max-height:320px; object-fit:contain;"
                     data-bs-toggle="modal"
                     data-bs-target="#imageModal"
                     onclick="showImage(this.src)">
            @else
                <div class="text-muted">Tidak ada gambar</div>
            @endif
        </div>


        {{-- GAMBAR TRIP --}}
        <div class="mb-3">
            <b>Bukti Trip</b><br>

            @if($item->gambar_trip)
                <img src="{{ asset('storage/'.$item->gambar_trip) }}"
                     class="img-fluid border mt-2 preview-image"
                     style="cursor:pointer; max-height:320px; object-fit:contain;"
                     data-bs-toggle="modal"
                     data-bs-target="#imageModal"
                     onclick="showImage(this.src)">
            @else
                <div class="text-muted">Tidak ada gambar</div>
            @endif
        </div>

    @empty
        <div class="alert alert-info">Tidak ada item</div>
    @endforelse


   <a href="{{ route('invoice.show', $invoice->mitra_id) }}" class="btn btn-secondary mt-3">Kembali</a>


</div>



{{-- MODAL FULLSCREEN --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark text-center">

            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body d-flex align-items-center justify-content-center">
                <img id="modalImage" src="" class="img-fluid" style="max-height:95vh;">
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
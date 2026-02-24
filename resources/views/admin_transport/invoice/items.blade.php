@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Invoice</h4>

    {{-- INFO MITRA --}}
    <div class="mb-4">
        <div class="d-flex mb-1">
            <div style="width:170px"><b>Mitra</b></div>
            <div>: {{ $item->invoice->mitra->nama_mitra ?? '-' }}</div>
        </div>
    </div>

    <hr class="my-4">

    {{-- DETAIL ITEM --}}
    <h5 class="mb-3">Detail Item</h5>

    {{-- STATUS ITEM --}}
    <div class="d-flex mb-2">
        <div style="width:170px"><b>Status Item</b></div>
        <div>:
            @php
                $sisa = $item->tagihan - $item->cicilan;
                $statusItem = $sisa <= 0 ? 'lunas' : 'belum lunas';
            @endphp

            <span class="badge px-3 py-2 text-light 
                bg-{{ $statusItem == 'lunas' ? 'success' : 'warning' }}">
                {{ strtoupper($statusItem) }}
            </span>
        </div>
    </div>

    <div class="d-flex mb-1">
        <div style="width:170px"><b>No Invoice</b></div>
        <div>: {{ $item->no_invoices ?? '-' }}</div>
    </div>

    <div class="d-flex mb-1">
        <div style="width:170px"><b>Tanggal Invoice</b></div>
        <div>:
            {{ $item->tanggal_invoices
                ? \Carbon\Carbon::parse($item->tanggal_invoices)->format('d-m-Y')
                : '-' }}
        </div>
    </div>

    <div class="d-flex mb-1">
        <div style="width:170px"><b>Item</b></div>
        <div>: {{ $item->item }}</div>
    </div>

    <div class="d-flex mb-1">
        <div style="width:170px"><b>Tanggal TF</b></div>
        <div>:
            {{ $item->tanggal_tf
                ? \Carbon\Carbon::parse($item->tanggal_tf)->format('d-m-Y')
                : '-' }}
        </div>
    </div>

    <div class="d-flex mb-1">
        <div style="width:170px"><b>Cicilan</b></div>
        <div>: Rp {{ number_format($item->cicilan,0,',','.') }}</div>
    </div>

    <div class="d-flex mb-1">
        <div style="width:170px"><b>Tagihan</b></div>
        <div>: Rp {{ number_format($item->tagihan,0,',','.') }}</div>
    </div>

    <div class="d-flex mb-4">
        <div style="width:170px"><b>Sisa Tagihan</b></div>
        <div>:
            <span class="fw-bold {{ $item->amount <= 0 ? 'text-success' : 'text-danger' }}">
                Rp {{ number_format($item->amount,0,',','.') }}
            </span>
        </div>
    </div>

    {{-- ======================= --}}
    {{-- BUKTI TRANSFER --}}
    {{-- ======================= --}}
    <div class="mb-4">
        <b>Bukti Transfer</b><br>

        @php
            $transferImages = [
                $item->gambar_transfer,
                $item->gambar_transfer1,
                $item->gambar_transfer2,
            ];
        @endphp

        @if(collect($transferImages)->filter()->count())
            <div class="d-flex flex-wrap gap-3 mt-2">
                @foreach($transferImages as $img)
                    @if($img)
                        <img src="{{ asset('storage/'.$img) }}"
                             class="img-thumbnail"
                             style="height:150px; cursor:pointer; object-fit:cover;"
                             data-bs-toggle="modal"
                             data-bs-target="#imageModal"
                             onclick="showImage(this.src)">
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-muted mt-2">Tidak ada gambar</div>
        @endif
    </div>

    {{-- ======================= --}}
    {{-- BUKTI TRIP --}}
    {{-- ======================= --}}
    <div class="mb-4">
        <b>Bukti Trip</b><br>

        @php
            $tripImages = [
                $item->gambar_trip,
                $item->gambar_trip1,
            ];
        @endphp

        @if(collect($tripImages)->filter()->count())
            <div class="d-flex flex-wrap gap-3 mt-2">
                @foreach($tripImages as $img)
                    @if($img)
                        <img src="{{ asset('storage/'.$img) }}"
                             class="img-thumbnail"
                             style="height:150px; cursor:pointer; object-fit:cover;"
                             data-bs-toggle="modal"
                             data-bs-target="#imageModal"
                             onclick="showImage(this.src)">
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-muted mt-2">Tidak ada gambar</div>
        @endif
    </div>

    <a href="{{ route('invoice.show', $item->invoice->mitra_id) }}" 
       class="btn btn-secondary mt-3">
        Kembali
    </a>

</div>

{{-- MODAL FULLSCREEN --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark text-center">

            <div class="modal-header border-0">
                <button type="button" 
                        class="btn-close btn-close-white ms-auto" 
                        data-bs-dismiss="modal"></button>
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
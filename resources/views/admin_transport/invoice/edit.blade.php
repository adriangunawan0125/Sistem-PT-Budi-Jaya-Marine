@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Item Invoice</h4>

    <form method="POST"
          action="{{ route('invoice-item.update', $item->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Item</label>
            <input type="text"
                   name="item"
                   class="form-control"
                   value="{{ $item->item }}"
                   required>
        </div>

        {{-- TANGGAL --}}
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ $item->tanggal }}">
        </div>

        {{-- CICILAN --}}
        <div class="mb-3">
            <label>Cicilan</label>
            <input type="text"
                   class="form-control rupiah"
                   data-hidden="cicilan"
                   value="Rp {{ number_format($item->cicilan, 0, ',', '.') }}">
            <input type="hidden"
                   name="cicilan"
                   value="{{ $item->cicilan }}">
        </div>

        {{-- TAGIHAN --}}
        <div class="mb-3">
            <label>Tagihan</label>
            <input type="text"
                   class="form-control rupiah"
                   data-hidden="tagihan"
                   value="Rp {{ number_format($item->tagihan, 0, ',', '.') }}">
            <input type="hidden"
                   name="tagihan"
                   value="{{ $item->tagihan }}">
        </div>

        {{-- GAMBAR TRIP --}}
        <div class="mb-3">
            <label>Gambar Trip</label><br>
            @if($item->gambar_trip)
                <img src="{{ asset('storage/'.$item->gambar_trip) }}"
                     width="120"
                     class="mb-2 d-block">
            @endif
            <input type="file"
                   name="gambar_trip"
                   class="form-control">
        </div>

        {{-- GAMBAR TRANSFER --}}
        <div class="mb-3">
            <label>Gambar Transfer</label><br>
            @if($item->gambar_transfer)
                <img src="{{ asset('storage/'.$item->gambar_transfer) }}"
                     width="120"
                     class="mb-2 d-block">
            @endif
            <input type="file"
                   name="gambar_transfer"
                   class="form-control">
        </div>

        {{-- TOMBOL --}}
        <div class="d-flex mt-4">
           

            <button class="btn btn-primary mr-1">
                Simpan
            </button>
             <a href="{{ route('invoice.show', $item->invoice->mitra_id) }}"
               class="btn btn-secondary mr-2">
                Batal
            </a>
        </div>

    </form>
</div>

{{-- ================= JS RUPIAH ================= --}}
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
        let raw = this.value.replace(/\D/g, '').replace(/^0+/, '');
        this.value = raw ? formatRupiah(raw) : '';
        document.querySelector(`input[name="${this.dataset.hidden}"]`).value = raw || 0;
    });
});
</script>
@endsection

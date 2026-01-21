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

        <div class="mb-3">
            <label>Cicilan</label>
            <input type="number"
                   name="cicilan"
                   class="form-control"
                   value="{{ $item->cicilan }}">
        </div>

        <div class="mb-3">
            <label>Tagihan</label>
            <input type="number"
                   name="tagihan"
                   class="form-control"
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

        <div class="d-flex justify-content-between">
            <a href="{{ route('invoice.show', $item->invoice->mitra_id) }}"
               class="btn btn-secondary">
               Kembali
            </a>

            <button class="btn btn-primary">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection

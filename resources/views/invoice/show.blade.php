@extends('layouts.app')

@section('content')
<div class="container">

@php
    $header = $invoices->first(); // header mitra
    $grandTotal = 0;
@endphp

<h4>Detail Invoice</h4>

<p><b>Mitra:</b> {{ $header->mitra->nama_mitra }}</p>

<a href="{{ route('invoice.create', ['mitra_id' => $header->mitra_id]) }}"
   class="btn btn-primary mb-3">
   + Invoice Baru
</a>

<table class="table table-bordered align-middle">
<thead>
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Item</th>
    <th>Cicilan</th>
    <th>Tagihan</th>
    <th>Amount</th>
    <th>Bukti TF</th>
    <th>Bukti Trip</th>
    <th width="120">Aksi</th>
</tr>
</thead>
<tbody>

@php $no = 1; @endphp

@foreach($invoices as $inv)
    @foreach($inv->items as $i)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $inv->tanggal }}</td>
        <td>{{ $i->item }}</td>
        <td>Rp {{ number_format($i->cicilan) }}</td>
        <td>Rp {{ number_format($i->tagihan) }}</td>
        <td>Rp {{ number_format($i->amount) }}</td>

       {{-- BUKTI TRANSFER --}}
<td>
    @foreach($header->transfers as $t)
        <img src="{{ asset('storage/'.$t->gambar) }}"
             width="60"
             class="img-thumbnail mb-1">
    @endforeach
</td>

{{-- BUKTI PERJALANAN --}}
<td>
    @foreach($header->trips as $t)
        <img src="{{ asset('storage/'.$t->gambar) }}"
             width="60"
             class="img-thumbnail mb-1">
    @endforeach
</td>


        <td>
            <!-- EDIT ITEM -->
            <button class="btn btn-sm btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#editItem{{ $i->id }}">
                ✏️
            </button>

            <!-- HAPUS ITEM -->
            <form action="{{ route('invoice-item.destroy', $i->id) }}"
                  method="POST"
                  style="display:inline-block"
                  onsubmit="return confirm('Hapus item ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">🗑️</button>
            </form>
        </td>
    </tr>

    @php $grandTotal += $i->amount; @endphp

    <!-- MODAL EDIT ITEM -->
    <div class="modal fade" id="editItem{{ $i->id }}" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">

          <form action="{{ route('invoice-item.update', $i->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-header">
              <h5 class="modal-title">Edit Item Invoice</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
              <div class="mb-2">
                <label>Item</label>
                <input type="text"
                       name="item"
                       class="form-control"
                       value="{{ $i->item }}"
                       required>
              </div>

              <div class="mb-2">
                <label>Cicilan</label>
                <input type="number"
                       name="cicilan"
                       class="form-control"
                       value="{{ $i->cicilan }}"
                       min="0">
              </div>

              <div class="mb-2">
                <label>Tagihan</label>
                <input type="number"
                       name="tagihan"
                       class="form-control"
                       value="{{ $i->tagihan }}"
                       min="0">
              </div>
            </div>

            <div class="modal-footer">
              <button class="btn btn-primary">💾 Simpan</button>
              <button type="button"
                      class="btn btn-secondary"
                      data-bs-dismiss="modal">
                  Batal
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>
    <!-- END MODAL -->

    @endforeach
@endforeach

<tr>
    <th colspan="5">TOTAL</th>
    <th>Rp {{ number_format($grandTotal) }}</th>
    <th colspan="3"></th>
</tr>

</tbody>
</table>

</div>
@endsection

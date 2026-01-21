@extends('layouts.app')

@section('content')
<div class="container">

<h4>Detail Invoice</h4>

<p><b>Mitra:</b> {{ $mitra->nama_mitra }}</p>
<p><b>Unit:</b> {{ $mitra->unit->nama_unit ?? '-' }}</p>

<a href="{{ route('invoice.create', ['mitra_id' => $mitra->id]) }}"
   class="btn btn-primary mb-3">
   + Invoice Baru
</a>

@if($invoices->isEmpty())
    <div class="alert alert-info">
        Mitra ini belum memiliki invoice.
    </div>
@endif

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

@php $grandTotal = 0; $no = 1; @endphp

@foreach($invoices as $inv)
    @foreach($inv->items as $item)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $inv->tanggal }}</td>
        <td>{{ $item->item }}</td>
        <td>Rp {{ number_format($item->cicilan) }}</td>
        <td>Rp {{ number_format($item->tagihan) }}</td>
        <td>Rp {{ number_format($item->amount) }}</td>

        <td>
            @if($item->gambar_transfer)
                <img src="{{ asset('storage/'.$item->gambar_transfer) }}"
                     width="60" class="img-thumbnail">
            @endif
        </td>

        <td>
            @if($item->gambar_trip)
                <img src="{{ asset('storage/'.$item->gambar_trip) }}"
                     width="60" class="img-thumbnail">
            @endif
        </td>

        <td>
            <!-- EDIT KE HALAMAN -->
            <a href="{{ route('invoice-item.edit', $item->id) }}"
               class="btn btn-sm btn-warning">
               ✏️
            </a>

            <!-- HAPUS -->
            <form action="{{ route('invoice-item.destroy', $item->id) }}"
                  method="POST"
                  style="display:inline-block"
                  onsubmit="return confirm('Hapus item ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">🗑️</button>
            </form>
        </td>
    </tr>

    @php $grandTotal += $item->amount; @endphp

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

@extends('layouts.app')

@section('content')
<div class="container">

   <h4>Detail Invoice</h4>

<p><b>Mitra:</b> {{ $mitra->nama_mitra }}</p>
<p><b>Unit:</b> {{ $mitra->unit->nama_unit ?? '-' }}</p>

<div class="mb-3">
    <a href="{{ route('invoice.create', ['mitra_id' => $mitra->id]) }}"
       class="btn btn-primary">
        + Invoice Baru
    </a>

    {{-- kalau mau print invoice pertama saja --}}
    @if($invoices->isNotEmpty())
        <a href="{{ route('invoice.print', $invoices->first()->id) }}"
           class="btn btn-danger">
            Print PDF
        </a>
    @endif
     @if($invoices->isEmpty())
    <div class="alert alert-info">
        Mitra ini belum memiliki invoice.
    </div>
@endif
</div>


    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Tanggal TF</th>
                <th>Cicilan</th>
                <th>Tagihan</th>
                <th>Amount</th>
                <th>Bukti TF</th>
                <th>Bukti Trip</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>

           @php
    $grandTotal = 0;
    $no = 1;
@endphp

@foreach ($invoices as $inv)
    @foreach ($inv->items as $item)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $item->item }}</td>
            <td>{{ $inv->tanggal }}</td>
            <td>Rp {{ number_format($item->cicilan) }}</td>
            <td>Rp {{ number_format($item->tagihan) }}</td>
            <td>Rp {{ number_format($item->amount) }}</td>

            <td>
                @if ($item->gambar_transfer)
                    <img src="{{ asset('storage/'.$item->gambar_transfer) }}"
                         width="60" class="img-thumbnail">
                @endif
            </td>

            <td>
                @if ($item->gambar_trip)
                    <img src="{{ asset('storage/'.$item->gambar_trip) }}"
                         width="60" class="img-thumbnail">
                @endif
            </td>

            <td class="text-center">
                <a href="{{ route('invoice-item.edit', $item->id) }}"
                   class="btn btn-sm btn-warning me-1">
                    Edit
                </a>

                <form action="{{ route('invoice-item.destroy', $item->id) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Hapus item ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        Hapus
                    </button>
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

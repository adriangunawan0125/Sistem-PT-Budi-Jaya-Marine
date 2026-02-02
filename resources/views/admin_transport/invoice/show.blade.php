@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Detail Invoice</h4>

    <p><b>Mitra:</b> {{ $mitra->nama_mitra }}</p>
    <p><b>Unit:</b> {{ $mitra->unit->nama_unit ?? '-' }}</p>

    <div class="mb-3">
        <a href="{{ route('invoice.create', ['mitra_id' => $mitra->id]) }}"
           class="btn btn-primary">
            + Invoice Baru
        </a>

        @if ($invoices->isNotEmpty())
            <a href="{{ route('invoice.print', $invoices->first()->id) }}"
               class="btn btn-danger">
                Print PDF
            </a>
        @endif
    </div>

    @if ($invoices->isEmpty())
        <div class="alert alert-info">
            Mitra ini belum memiliki invoice.
        </div>
    @endif

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr class="text-center">
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
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $item->item }}</td>
                        <td class="text-center">
                            {{ $item->tanggal ?? '-' }}
                        </td>
                        <td>Rp {{ number_format($item->cicilan) }}</td>
                        <td>Rp {{ number_format($item->tagihan) }}</td>
                        <td><b>Rp {{ number_format($item->amount) }}</b></td>

                        <td class="text-center">
                            @if ($item->gambar_transfer)
                                <img src="{{ asset('storage/'.$item->gambar_transfer) }}"
                                     width="60"
                                     class="img-thumbnail">
                            @else
                                -
                            @endif
                        </td>

                        <td class="text-center">
                            @if ($item->gambar_trip)
                                <img src="{{ asset('storage/'.$item->gambar_trip) }}"
                                     width="60"
                                     class="img-thumbnail">
                            @else
                                -
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('invoice-item.edit', $item->id) }}"
                               class="btn btn-sm btn-warning mb-1">
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
    <th colspan="5" class="text-end">TOTAL</th>
    <th>Rp {{ number_format($grandTotal) }}</th>
</tr>


        </tbody>
    </table>
     <a href="{{ route('invoice.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>

</div>
@endsection

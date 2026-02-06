@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Detail Invoice</h4>

    <p><b>Mitra:</b> {{ $mitra->nama_mitra }}</p>
    <p><b>Unit:</b> {{ $mitra->unit->nama_unit ?? '-' }}</p>

   
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
                            {{ $item->tanggal
                                ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')
                                : '-' }}
                        </td>
                        <td>Rp {{ number_format($item->cicilan,0,',','.') }}</td>
                        <td>Rp {{ number_format($item->tagihan,0,',','.') }}</td>
                        <td><b>Rp {{ number_format($item->amount,0,',','.') }}</b></td>

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

                    </tr>

                    @php $grandTotal += $item->amount; @endphp
                @endforeach
            @endforeach

            <tr>
                <th colspan="5" class="text-end">TOTAL</th>
                <th>Rp {{ number_format($grandTotal,0,',','.') }}</th>
                <th colspan="3"></th>
            </tr>

        </tbody>
    </table>

    <a href="{{ route('invoice.rekap') }}"
       class="btn btn-secondary mt-2">
        Kembali
    </a>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Detail Invoice</h4>

    <p><b>Mitra:</b> {{ $mitra->nama_mitra }}</p>
    <p><b>Unit:</b> {{ $mitra->unit->nama_unit ?? '-' }}</p>

    <div class="mb-3 d-flex gap-2" >
        <a style="margin-right: 7px" href="{{ route('invoice.create', ['mitra_id' => $mitra->id]) }}"
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


    <style>
        .invoice-table {
            table-layout: fixed;
            width: 100%;
        }

        .invoice-table td,
        .invoice-table th {
            vertical-align: middle;
        }

        .col-no{width:50px}
        .col-inv{width:120px}
        .col-tgl{width:120px}
        .col-num{width:130px}
        .col-aksi{width:210px}

        .item-cell{
            word-break: break-word;
            white-space: normal;
        }

        .aksi-group{
            display:flex;
            justify-content:center;
            gap:6px;
            flex-wrap:nowrap;
        }

        .aksi-group form{
            display:inline-flex;
            margin:0;
        }
    </style>


    <table class="table table-bordered table-sm align-middle invoice-table">
        <thead class="table-light">
            <tr class="text-center">
                <th class="col-no">No</th>
                <th class="col-inv">No Invoice</th>
                <th>Item</th>
                <th class="col-tgl">Tanggal TF</th>
                <th class="col-num">Cicilan</th>
                <th class="col-num">Tagihan</th>
                <th class="col-num">Amount</th>
                <th class="col-aksi">Aksi</th>
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

                        <td class="text-center text-nowrap">
                            {{ $item->no_invoices ?? '-' }}
                        </td>

                        <td class="item-cell">
                            {{ $item->item }}
                        </td>

                        <td class="text-center text-nowrap">
                            {{ $item->tanggal_tf ?? '-' }}
                        </td>

                        <td class="text-end text-nowrap">{{ $item->cicilan_rp }}</td>
                        <td class="text-end text-nowrap">{{ $item->tagihan_rp }}</td>
                        <td class="text-end text-nowrap fw-bold">{{ $item->amount_rp }}</td>

                        <td>
                            <div class="aksi-group">

                                <a href="{{ route('invoice.items',$inv->id) }}"
                                   class="btn btn-info btn-sm">
                                    Detail
                                </a>

                                <a href="{{ route('invoice-item.edit', $item->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('invoice-item.destroy', $item->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    @php $grandTotal += $item->amount; @endphp
                @endforeach
            @endforeach

            <tr>
                <th colspan="6" class="text-end">TOTAL</th>
                <th class="text-end text-nowrap">
                    Rp {{ number_format($grandTotal,0,',','.') }}
                </th>
                <th></th>
            </tr>

        </tbody>
    </table>

    <a href="{{ route('invoice.index') }}" class="btn btn-secondary">
        Kembali
    </a>

</div>
@endsection

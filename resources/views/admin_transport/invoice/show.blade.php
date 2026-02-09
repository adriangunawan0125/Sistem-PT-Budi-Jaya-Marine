@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Detail Invoice</h4>

    <p><b>Mitra:</b> {{ $mitra->nama_mitra }}</p>
    <p><b>Unit:</b> {{ $mitra->unit->nama_unit ?? '-' }}</p>

    @php
        $editableInvoice = $invoice && $invoice->items->isNotEmpty()
            ? $invoice
            : null;
    @endphp

    {{-- ACTION BUTTONS --}}
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('invoice.create', ['mitra_id' => $mitra->id]) }}"
           class="btn btn-primary" style="margin-right: 7px">
            + Invoice Baru
        </a>

        @if ($editableInvoice)
            <a href="{{ route('invoice.edit', $editableInvoice->id) }}"
               class="btn btn-warning" style="margin-right: 7px">
                Edit Invoice
            </a>

            <a href="{{ route('invoice.print', $editableInvoice->id) }}"
               class="btn btn-danger" style="margin-right: 7px">
                Print PDF
            </a>
        @endif
    </div>

    @if (!$invoice || $invoice->items->isEmpty())
        <div class="alert alert-info">
            Mitra ini belum memiliki invoice.
        </div>
    @endif

    <style>
        .invoice-table{
            table-layout: fixed;
            min-width: 1400px;
        }
        .invoice-table th,
        .invoice-table td{
            vertical-align: middle;
        }
        .col-no     { width: 60px }
        .col-inv    { width: 160px }
        .col-item   { width: 320px }
        .col-tgl    { width: 130px }
        .col-num    { width: 150px }
        .col-aksi   { width: 160px }

        .item-cell{
            white-space: normal;
            word-break: break-word;
        }
        .nowrap{
            white-space: nowrap;
        }
    </style>

    @if ($invoice && $invoice->items->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-bordered table-sm invoice-table">
            <thead class="table-light text-center">
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-inv">No Invoice</th>
                    <th class="col-item">Item</th>
                    <th class="col-tgl">Tanggal TF</th>
                    <th class="col-num">Cicilan</th>
                    <th class="col-num">Tagihan</th>
                    <th class="col-num">Amount</th>
                    <th class="col-aksi">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $no = 1;
                    $grandTotal = 0;
                @endphp

                @foreach ($invoice->items as $item)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>

                        <td class="text-center nowrap">
                            {{ $item->no_invoices ?? '-' }}
                        </td>

                        <td class="item-cell">
                            {{ $item->item }}
                        </td>

                        <td class="text-center nowrap">
                            {{ $item->tanggal_tf
                                ? \Carbon\Carbon::parse($item->tanggal_tf)->format('d-m-Y')
                                : '-' }}
                        </td>

                        <td class="text-end nowrap">
                            {{ $item->cicilan_rp }}
                        </td>

                        <td class="text-end nowrap">
                            {{ $item->tagihan_rp }}
                        </td>

                        <td class="text-end nowrap fw-bold">
                            {{ $item->amount_rp }}
                        </td>

                        <td class="text-center nowrap">
                            <a href="{{ route('invoice-item.show', $item->id) }}"
                               class="btn btn-info btn-sm">
                                Detail
                            </a>
                        </td>
                    </tr>

                    @php $grandTotal += $item->amount; @endphp
                @endforeach

                <tr class="table">
                    <th colspan="6" class="text-end">TOTAL</th>
                    <th class="text-end">
                        Rp {{ number_format($grandTotal,0,',','.') }}
                    </th>
                    <th></th>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <a href="{{ route('invoice.index') }}" class="btn btn-secondary mt-3">
        Kembali
    </a>

</div>
@endsection

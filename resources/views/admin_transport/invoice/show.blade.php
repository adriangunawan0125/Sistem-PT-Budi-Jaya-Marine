@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Detail Invoice</h4>

    <div class="mb-3">
        <div><b>Mitra:</b> {{ $mitra->nama_mitra }}</div>
        <div><b>Unit:</b> {{ $mitra->unit->nama_unit ?? '-' }}</div>
    </div>

    @php
        $editableInvoice = $invoice && $invoice->items->isNotEmpty()
            ? $invoice
            : null;
    @endphp

    {{-- ACTION BUTTONS --}}
    <div class="mb-3 d-flex flex-wrap gap-2">
        <a href="{{ route('invoice.create', ['mitra_id' => $mitra->id]) }}"
           class="btn btn-primary btn-sm" style="margin-right: 4px">
            + Invoice Baru
        </a>

        @if ($editableInvoice)
            <a href="{{ route('invoice.edit', $editableInvoice->id) }}"
               class="btn btn-warning btn-sm" style="margin-right: 4px">
                Edit Invoice
            </a>

            <a href="{{ route('invoice.print', $editableInvoice->id) }}"
               class="btn btn-danger btn-sm trigger-loading"
               data-loading-text="Membuat PDF...">
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
            table-layout: auto;
            width: 100%;
        }

        .invoice-table th{
            font-size:13px;
            padding:10px;
            text-align:center;
            background:#f8f9fa;
        }

        .invoice-table td{
            font-size:13px;
            padding:10px;
            vertical-align:middle;
        }

        .invoice-table tbody tr:hover{
            background:#f5f7fa;
        }

        .nowrap{
            white-space: nowrap;
        }

        .item-cell{
            white-space: normal;
            word-break: break-word;
            max-width: 250px;
        }

        .total-row{
            background:#f1f3f5;
            font-weight:600;
        }
    </style>

    @if ($invoice && $invoice->items->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-bordered table-hover invoice-table">
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                     <th style="width:130px;">Tanggal Invoice</th>
                    <th style="width:160px;">No Invoice</th>
                    <th>Item</th>
                   
                    <th style="width:120px;">Tgl TF</th>
                    <th style="width:120px;">Cicilan</th>
                    <th style="width:120px;">Tagihan</th>
                    <th style="width:130px;">Sisa</th>
                    <th style="width:100px;">Aksi</th>
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
   {{-- TANGGAL INVOICE PER ITEM --}}
                        <td class="text-center nowrap">
                            {{ $item->tanggal_invoices
                                ? \Carbon\Carbon::parse($item->tanggal_invoices)->format('d-m-Y')
                                : '-' }}
                        </td>
                        
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

                        <td class="text-center">
                            <a href="{{ route('invoice-item.show', $item->id) }}"
                               class="btn btn-info btn-sm trigger-loading">
                                Detail
                            </a>
                        </td>
                    </tr>

                    @php $grandTotal += $item->amount; @endphp
                @endforeach

                <tr class="total-row">
                    <td colspan="7" class="text-end">TOTAL</td>
                    <td class="text-end">
                        Rp {{ number_format($grandTotal,0,',','.') }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <a href="{{ route('invoice.index') }}"
       class="btn btn-secondary btn-sm mt-3 trigger-loading">
        Kembali
    </a>

</div>


{{-- LOADING MODAL --}}
<div class="modal fade"
     id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3"
                     style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold" id="loadingText">
                    Memuat data...
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const modalEl = document.getElementById("loadingModal");
    const loadingText = document.getElementById("loadingText");
    const loadingModal = new bootstrap.Modal(modalEl);

    document.querySelectorAll(".trigger-loading").forEach(el => {
        el.addEventListener("click", function () {
            const text = el.getAttribute("data-loading-text");
            loadingText.innerText = text ?? "Memuat data...";
            loadingModal.show();
        });
    });

});
</script>
@endsection
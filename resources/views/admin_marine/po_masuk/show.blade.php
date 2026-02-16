@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================= PAGE TITLE ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Detail PO Klien</h2>

        <div class="d-flex gap-2">

            <a href="{{ route('po-masuk.index') }}" 
               class="btn btn-secondary btn-sm">
                ← Kembali
            </a>

            {{-- APPROVE --}}
            @if($poMasuk->status == 'draft')
            <form action="{{ route('po-masuk.approve',$poMasuk->id) }}"
                  method="POST">
                @csrf
                @method('PATCH')
                <button class="btn btn-success btn-sm">
                    Approve PO
                </button>
            </form>
            @endif

            {{-- CLOSE --}}
            @if($poMasuk->status == 'delivered')
            <form action="{{ route('po-masuk.close',$poMasuk->id) }}"
                  method="POST">
                @csrf
                @method('PATCH')
                <button class="btn btn-danger btn-sm">
                    Close PO
                </button>
            </form>
            @endif

        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- ========== CLIENT PO (HEADER + ITEM SATU CARD) ========= --}}
    {{-- ========================================================= --}}
    <div class="card mb-4 shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>PO dari Klien (Harga Jual)</strong>

            <a href="{{ route('po-masuk.edit',$poMasuk->id) }}"
               class="btn btn-sm btn-warning">
                Edit PO Klien
            </a>
        </div>

        <div class="card-body">

            {{-- ===== HEADER INFO ===== --}}
            <div class="row mb-4">

                <div class="col-md-6 mb-3">
                    <strong>No PO Klien</strong><br>
                    {{ $poMasuk->no_po_klien }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Tanggal PO</strong><br>
                    {{ \Carbon\Carbon::parse($poMasuk->tanggal_po)->format('d M Y') }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Mitra</strong><br>
                    {{ $poMasuk->mitra->nama_mitra ?? '-' }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Vessel</strong><br>
                    {{ $poMasuk->vessel->nama_vessel ?? '-' }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Status</strong><br>
                    <span class="badge text-light
                        @if($poMasuk->status == 'draft') bg-secondary
                        @elseif($poMasuk->status == 'approved') bg-primary
                        @elseif($poMasuk->status == 'processing') bg-warning text-dark
                        @elseif($poMasuk->status == 'delivered') bg-info
                        @elseif($poMasuk->status == 'closed') bg-success
                        @endif">
                        {{ strtoupper($poMasuk->status) }}
                    </span>
                </div>

            </div>

            <hr>

            {{-- ===== ITEM TABLE ===== --}}
            <h5 class="mb-3">Item PO Klien</h5>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th width="80">Qty</th>
                            <th width="80">Unit</th>
                            <th width="150">Harga Jual</th>
                            <th width="150">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($poMasuk->items as $item)
                            <tr>
                                <td>{{ $item->item }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>Rp {{ number_format($item->price_jual,0,',','.') }}</td>
                                <td>Rp {{ number_format($item->amount,0,',','.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Belum ada item
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <div class="card-footer text-end fw-bold">
            Nilai PO klien (Total Jual) :
            Rp {{ number_format($poMasuk->total_jual ?? 0,0,',','.') }}
        </div>

    </div>


    {{-- ================= PO SUPPLIER ================= --}}
    <div class="card mb-4 shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>PO ke Supplier (Harga Beli)</strong>

            <a href="{{ route('po-supplier.create', $poMasuk->id) }}"
               class="btn btn-success btn-sm">
                + Buat PO Supplier
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Supplier</th>
                        <th>No PO</th>
                        <th width="150">Grand Total</th>
                        <th width="120">Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($poMasuk->poSuppliers as $sup)
                        <tr>
                            <td>{{ $sup->nama_perusahaan ?? '-' }}</td>
                            <td>{{ $sup->no_po_internal }}</td>
                            <td>Rp {{ number_format($sup->grand_total ?? 0,0,',','.') }}</td>
                            <td>
                                <span class="badge bg-info text-light">
                                    {{ strtoupper($sup->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('po-supplier.show', $sup->id) }}"
                                   class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Belum ada PO Supplier
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer text-end fw-bold">
            Nilai PO Kita (Total Beli):
            Rp {{ number_format($poMasuk->poSuppliers->sum('grand_total'),0,',','.') }}
        </div>

    </div>


    {{-- ================= DELIVERY ORDER ================= --}}
    <div class="card mb-4 shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Delivery Order</strong>

            <a href="{{ route('delivery-order.create', $poMasuk->id) }}"
               class="btn btn-warning btn-sm">
                + Buat Delivery Order
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No DO</th>
                        <th width="150">Tanggal</th>
                        <th width="120">Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($poMasuk->deliveryOrders as $do)
                        <tr>
                            <td>{{ $do->no_do ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($do->tanggal_do)->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-info text-light">
                                    {{ strtoupper($do->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('delivery-order.show', $do->id) }}"
                                   class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada Delivery Order
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

{{-- ================= PENGELUARAN PO ================= --}}
<div class="card mb-4 shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Pengeluaran Lain-Lain</strong>

        {{-- TOMBOL REDIRECT KE HALAMAN CREATE --}}
        <a href="{{ route('pengeluaran-po.create', $poMasuk->id) }}"
           class="btn btn-primary btn-sm">
            + Tambah Pengeluaran
        </a>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th width="80">Qty</th>
                        <th width="120">Harga</th>
                        <th width="150">Amount</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @php
                        $totalPengeluaran = $poMasuk->pengeluaran->sum('amount');
                    @endphp

                    @forelse ($poMasuk->pengeluaran as $exp)
                    <tr>
                        <td>{{ $exp->item }}</td>
                        <td>{{ $exp->qty }}</td>
                        <td>Rp {{ number_format($exp->price,0,',','.') }}</td>
                        <td>Rp {{ number_format($exp->amount,0,',','.') }}</td>
                        <td>
                            {{-- DETAIL BUTTON --}}
                            <a href="{{ route('pengeluaran-po.show',$exp->id) }}"
                               class="btn btn-sm btn-info">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada pengeluaran
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer text-end fw-bold">
        Total Pengeluaran :
        Rp {{ number_format($totalPengeluaran ?? 0,0,',','.') }}
    </div>

</div>

{{-- ================= INVOICE PO ================= --}}
<div class="card mb-4 shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Invoice PO</strong>

        <a href="{{ route('invoice-po.create', $poMasuk->id) }}"
           class="btn btn-success btn-sm">
            + Buat Invoice
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>No Invoice</th>
                    <th width="140">Tanggal</th>
                    <th width="120">Periode</th>
                    <th width="160">Grand Total</th>
                    <th width="120">Status</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $totalInvoice = $poMasuk->invoicePos->sum('grand_total');
                @endphp

                @forelse($poMasuk->invoicePos as $inv)
                    <tr>
                        <td>{{ $inv->no_invoice }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($inv->tanggal_invoice)->format('d M Y') }}
                        </td>

                        <td>
                            {{ $inv->periode ?? '-' }}
                        </td>

                        <td>
                            Rp {{ number_format($inv->grand_total,0,',','.') }}
                        </td>

                        <td>
                            <span class="badge text-light
                                @if($inv->status == 'draft') bg-secondary
                                @elseif($inv->status == 'issued') bg-primary
                                @elseif($inv->status == 'paid') bg-success
                                @elseif($inv->status == 'cancelled') bg-danger
                                @endif">
                                {{ strtoupper($inv->status) }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('invoice-po.show',$inv->id) }}"
                               class="btn btn-sm btn-primary">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada Invoice
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <div class="card-footer text-end fw-bold">
        Total Invoice :
        Rp {{ number_format($totalInvoice ?? 0,0,',','.') }}
    </div>

</div>

    {{-- ================= MARGIN ================= --}}
    <div class="card shadow-sm">

        <div class="card-header fw-bold">
           <strong>Summary</strong> 
        </div>

        <div class="card-body">

           @php
    $totalBeli = $poMasuk->poSuppliers->sum('grand_total');
    $totalPengeluaran = $poMasuk->pengeluaran->sum('amount');
    $margin = $poMasuk->total_jual - $totalBeli - $totalPengeluaran;
@endphp


            <table class="table table-bordered">
                <tr>
                    <th width="200">Nilai PO Klien</th>
                    <td>Rp {{ number_format($poMasuk->total_jual,0,',','.') }}</td>
                </tr>
                <tr>
                    <th>Nilai PO Kita</th>
                    <td>Rp {{ number_format($totalBeli,0,',','.') }}</td>
                </tr>
                <tr>
    <th>Total Pengeluaran</th>
    <td>Rp {{ number_format($totalPengeluaran,0,',','.') }}</td>
</tr>

                <tr>
                    <th>Margin</th>
                    <td class="fw-bold 
                        {{ $margin > 0 ? 'text-success' : ($margin < 0 ? 'text-danger' : '') }}">
                        Rp {{ number_format($margin,0,',','.') }}
                    </td>
                </tr>
            </table>

        </div>

    </div>

</div>
@endsection

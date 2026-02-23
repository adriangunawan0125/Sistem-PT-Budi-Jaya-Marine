@extends('layouts.app')

@section('content')
<div class="container">

<style>
.table-supplier th,
.table-supplier td{
    font-size:13px;
    padding:8px 10px;
    vertical-align:middle;
}

.supplier-footer-label{
    font-size:12px;
    color:#6c757d;
}

.supplier-footer-value{
    font-size:16px;
    font-weight:700;
}

.detail-label{
    font-size:12px;
    color:#6c757d;
    margin-bottom:2px;
}

.detail-value{
    font-weight:600;
    font-size:14px;
}
</style>


{{-- ================= PO MASUK ================= --}}
<div class="card shadow-sm border-0 mb-5">

    {{-- HEADER --}}
    {{-- HEADER --}}
<div class="card-header bg-white d-flex justify-content-between align-items-center">

    <div class="fw-semibold small">
        Informasi PO Klien
    </div>

    <div class="d-flex align-items-center gap-3">
 <a href="{{ route('po-masuk.index') }}"
           class="btn btn-secondary btn-sm" style="margin-right: 6px">
            Kembali
        </a>
         <a href="{{ route('po-masuk.edit',$poMasuk->id) }}"
           class="btn btn-warning btn-sm" style="margin-right: 6px">
            Edit PO-Klien
        </a>
        {{-- STATUS DROPDOWN --}}
        <form action="{{ route('po-masuk.update-status', $poMasuk->id) }}" id="statusForm"
              method="POST"
              class="mb-0">
            @csrf
            @method('PATCH')

            <select name="status"
                    class="form-control form-control-sm"
                    onchange="this.form.submit()">

                <option value="draft"
                    {{ $poMasuk->status == 'draft' ? 'selected' : '' }}>
                    Draft
                </option>

                <option value="approved"
                    {{ $poMasuk->status == 'approved' ? 'selected' : '' }}>
                    Approved
                </option>

                <option value="processing"
                    {{ $poMasuk->status == 'processing' ? 'selected' : '' }}>
                    Processing
                </option>

                <option value="delivered"
                    {{ $poMasuk->status == 'delivered' ? 'selected' : '' }}>
                    Delivered
                </option>

                <option value="closed"
                    {{ $poMasuk->status == 'closed' ? 'selected' : '' }}>
                    Closed
                </option>

            </select>
        </form>

       

    </div>

</div>


    {{-- BODY INFO --}}
    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="detail-label">No PO</div>
                <div class="detail-value">
                    {{ $poMasuk->no_po_klien }}
                </div>
            </div>

            <div class="col-md-4">
                <div class="detail-label">Tanggal</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($poMasuk->tanggal_po)->format('d M Y') }}
                </div>
            </div>

            <div class="col-md-4">
                <div class="detail-label">Status</div>
                <span class="badge px-3 py-2 text-light
                    @if($poMasuk->status == 'draft') bg-secondary
                    @elseif($poMasuk->status == 'approved') bg-primary
                    @elseif($poMasuk->status == 'processing') bg-warning 
                    @elseif($poMasuk->status == 'delivered') bg-info
                    @elseif($poMasuk->status == 'closed') bg-success
                    @endif">
                    {{ strtoupper($poMasuk->status) }}
                </span>
            </div>

            <div class="col-md-6">
                <div class="detail-label">Mitra</div>
                <div class="detail-value">
                    {{ $poMasuk->mitra->nama_mitra ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="detail-label">Vessel</div>
                <div class="detail-value">
                    {{ $poMasuk->vessel->nama_vessel ?? '-' }}
                </div>
            </div>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-supplier mb-0">

                <thead class="table-light text-center">
                    <tr>
                        <th>Item</th>
                        <th width="80">Qty</th>
                        <th width="90">Unit</th>
                        <th width="150">Harga Jual</th>
                        <th width="150">Amount</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($poMasuk->items as $item)
                    <tr>
                        <td>{{ $item->item }}</td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-center">{{ $item->unit }}</td>
                        <td class="text-end fw-semibold">
                            Rp {{ number_format($item->price_jual,0,',','.') }}
                        </td>
                        <td class="text-end fw-semibold">
                            Rp {{ number_format($item->amount,0,',','.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="text-center text-muted py-4 small">
                            Belum ada item
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>


    {{-- FOOTER --}}
    <div class="card-footer bg-white text-end">

        <div class="supplier-footer-label">
            Total Nilai PO Klien
        </div>

        <div class="supplier-footer-value text-primary">
            Rp {{ number_format($poMasuk->total_jual ?? 0,0,',','.') }}
        </div>

    </div>

</div>


<style>
.table-supplier th,
.table-supplier td{
    font-size:13px;
    padding:8px 10px;
    vertical-align:middle;
}

.supplier-footer-label{
    font-size:12px;
    color:#6c757d;
}

.supplier-footer-value{
    font-size:16px;
    font-weight:700;
}
</style>


{{-- ================= PO SUPPLIER ================= --}}
<div class="card shadow-sm border-0 mb-5">

    {{-- HEADER --}}
    <iv class="card-header bg-white d-flex justify-content-between align-items-center">

        <div class="fw-semibold small">
            PO ke Supplier (Harga Beli)
        </div>

        <a href="{{ route('po-supplier.create', $poMasuk->id) }}"
           class="btn btn-success btn-sm">
            + Buat PO Supplier
        </a>

    </iv>


    {{-- BODY --}}
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-supplier mb-0">

                <thead class="table-light text-center">
                    <tr>
                        <th>Supplier</th>
                        <th width="170">No PO Internal</th>
                        <th width="160" class="text-end">Grand Total</th>
                        <th width="130">Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse ($poMasuk->poSuppliers as $sup)

                    @php
                        $badge = match($sup->status){ 
                            'approved' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            'processing' => 'bg-warning',
                            default => 'bg-secondary'
                        };
                    @endphp

                    <tr>

                        <td class="fw-semibold">
                            {{ $sup->nama_perusahaan ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $sup->no_po_internal }}
                        </td>

                        <td class="text-end fw-semibold">
                            Rp {{ number_format($sup->grand_total ?? 0,0,',','.') }}
                        </td>

                     <td class="text-center">

    @php
        $badgeClass = match($sup->status) {
            'draft'      => 'bg-secondary',
            'sent'       => 'bg-primary',
            'approved'   => 'bg-info text-light',
            'processing' => 'bg-warning',
            'completed'  => 'bg-success',
            'cancelled'  => 'bg-danger',
            default      => 'bg-dark',
        };
    @endphp

    <span class="badge text-light px-3 py-2 {{ $badgeClass }}">
        {{ strtoupper($sup->status ?? '-') }}
    </span>

</td>

                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('po-supplier.show', $sup->id) }}"
                                   class="btn btn-primary btn-sm btnDetail">
                                    Detail
                                </a>
                            </div>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="5"
                            class="text-center text-muted py-4 small">
                            Belum ada PO Supplier
                        </td>
                    </tr>
                @endforelse

                </tbody>

            </table>
        </div>

    </div>


    {{-- FOOTER --}}
    <div class="card-footer bg-white text-end">

        <div class="supplier-footer-label">
            Total Nilai PO Supplier (Total Beli)
        </div>

        <div class="supplier-footer-value text-danger">
            Rp {{ number_format($poMasuk->poSuppliers->sum('grand_total'),0,',','.') }}
        </div>

    </div>

</div>

{{-- ================= DELIVERY ORDER ================= --}}
<div class="card shadow-sm border-0 mb-5">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-semibold small">
            Delivery Order
        </div>

        <a href="{{ route('delivery-order.create', $poMasuk->id) }}"
           class="btn btn-success btn-sm">
            + Buat Delivery Order
        </a>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-supplier mb-0">

                <thead class="table-light text-center">
                    <tr>
                        <th>No DO</th>
                        <th width="150">Tanggal</th>
                        <th width="130">Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($poMasuk->deliveryOrders as $do)
                    <tr>
                        <td>{{ $do->no_do ?? '-' }}</td>

                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($do->tanggal_do)->format('d M Y') }}
                        </td>

                        <td class="text-center">
                            <span class="badge bg-info text-light px-3 py-2">
                                {{ strtoupper($do->status) }}
                            </span>
                        </td>

                        <td class="text-center">
                            <a href="{{ route('delivery-order.show', $do->id) }}"
                               class="btn btn-sm btn-primary btnDetail">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="text-center text-muted py-4 small">
                            Belum ada Delivery Order
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>

{{-- ================= PENGELUARAN PO ================= --}}
<div class="card shadow-sm border-0 mb-5">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-semibold small">
            Pengeluaran Lain-Lain
        </div>

        <a href="{{ route('pengeluaran-po.create', $poMasuk->id) }}"
           class="btn btn-success btn-sm">
            + Tambah Pengeluaran
        </a>
    </div>

    @php
        $totalPengeluaran = $poMasuk->pengeluaran->sum('amount');
    @endphp

    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-supplier mb-0">

                <thead class="table-light text-center">
                    <tr>
                        <th>Item</th>
                        <th width="80">Qty</th>
                        <th width="150">Harga</th>
                        <th width="150">Amount</th>
                        <th width="170">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($poMasuk->pengeluaran as $exp)
                    <tr>
                        <td>{{ $exp->item }}</td>

                        <td class="text-center">
                            {{ $exp->qty }}
                        </td>

                        <td class="text-end fw-semibold">
                            Rp {{ number_format($exp->price,0,',','.') }}
                        </td>

                        <td class="text-end fw-semibold">
                            Rp {{ number_format($exp->amount,0,',','.') }}
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">

                                <a href="{{ route('pengeluaran-po.show',$exp->id) }}"
                                   class="btn btn-sm btn-primary btnDetail" style="margin-right: 7px">
                                    Detail
                                </a>

                                <a href="{{ route('pengeluaran-po.edit',$exp->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="text-center text-muted py-4 small">
                            Belum ada pengeluaran
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <div class="card-footer bg-white text-end">

        <div class="supplier-footer-label">
            Total Pengeluaran
        </div>

        <div class="supplier-footer-value text-danger">
            Rp {{ number_format($totalPengeluaran ?? 0,0,',','.') }}
        </div>

    </div>

</div>

{{-- ================= INVOICE PO ================= --}}
<div class="card shadow-sm border-0 mb-5">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-semibold small">
            Invoice PO
        </div>

        <a href="{{ route('invoice-po.create', $poMasuk->id) }}"
           class="btn btn-success btn-sm">
            + Buat Invoice
        </a>
    </div>

    @php
        $totalInvoice = $poMasuk->invoicePos->sum('grand_total');
    @endphp

    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-supplier mb-0">

                <thead class="table-light text-center">
                    <tr>
                        <th>No Invoice</th>
                        <th width="140">Tanggal</th>
                        <th width="120">Periode</th>
                        <th width="160">Grand Total</th>
                        <th width="120">Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($poMasuk->invoicePos as $inv)
                    <tr>
                        <td>{{ $inv->no_invoice }}</td>

                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($inv->tanggal_invoice)->format('d M Y') }}
                        </td>

                        <td class="text-center">
                            {{ $inv->periode ?? '-' }}
                        </td>

                        <td class="text-end fw-semibold">
                            Rp {{ number_format($inv->grand_total,0,',','.') }}
                        </td>

                        <td class="text-center">
                            <span class="badge text-light px-3 py-2
                                @if($inv->status == 'draft') bg-secondary
                                @elseif($inv->status == 'issued') bg-primary
                                @elseif($inv->status == 'paid') bg-success
                                @elseif($inv->status == 'cancelled') bg-danger
                                @endif">
                                {{ strtoupper($inv->status) }}
                            </span>
                        </td>

                        <td class="text-center">
                            <a href="{{ route('invoice-po.show',$inv->id) }}"
                               class="btn btn-sm btn-primary btnDetail">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            class="text-center text-muted py-4 small">
                            Belum ada Invoice
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <div class="card-footer bg-white text-end">

        <div class="supplier-footer-label">
            Total Invoice
        </div>

        <div class="supplier-footer-value text-success">
            Rp {{ number_format($totalInvoice ?? 0,0,',','.') }}
        </div>

    </div>

</div>

{{-- ================= TIMESHEET ================= --}}
<div class="card shadow-sm border-0 mb-5">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-semibold small">
            Timesheet
        </div>

        <a href="{{ route('timesheet.create', $poMasuk->id) }}"
           class="btn btn-success btn-sm">
            + Buat Timesheet
        </a>
    </div>

    @php
        $totalHours = $poMasuk->timesheets->sum('total_hours');
    @endphp

    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-supplier mb-0">

                <thead class="table-light text-center">
                    <tr>
                        <th>Project</th>
                        <th width="150">Manpower</th>
                        <th width="140">Total Jam</th>
                        <th width="120">Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($poMasuk->timesheets as $ts)
                    <tr>
                        <td class="fw-semibold">
                            {{ $ts->project }}
                        </td>

                        <td class="text-center">
                            {{ $ts->manpower }}
                        </td>

                        <td class="text-end fw-semibold">
                            {{ number_format($ts->total_hours,2) }} Jam
                        </td>

                        <td class="text-center">
                            <span class="badge text-light px-3 py-2
                                @if($ts->status == 'draft') bg-secondary
                                @elseif($ts->status == 'approved') bg-primary
                                @elseif($ts->status == 'completed') bg-success
                                @endif">
                                {{ strtoupper($ts->status) }}
                            </span>
                        </td>

                        <td class="text-center">
                            <a href="{{ route('timesheet.show', $ts->id) }}"
                               class="btn btn-sm btn-primary btnDetail">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="text-center text-muted py-4 small">
                            Belum ada Timesheet
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <div class="card-footer bg-white text-end">

        <div class="supplier-footer-label">
            Total Jam Kerja
        </div>

        <div class="supplier-footer-value text-primary">
            {{ number_format($totalHours ?? 0,2) }} Jam
        </div>

    </div>

</div>

{{-- ================= WORKING REPORT ================= --}}
<div class="card shadow-sm border-0 mb-5">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-semibold small">
            Working Report
        </div>

        <a href="{{ route('working-report.create', $poMasuk->id) }}"
           class="btn btn-success btn-sm">
            + Buat Working Report
        </a>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-supplier mb-0">

                <thead class="table-light text-center">
                    <tr>
                        <th>Project</th>
                        <th width="150">Periode</th>
                        <th width="140">Total Kolom</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($poMasuk->workingReports as $wr)
                    <tr>
                        <td class="fw-semibold">
                            {{ $wr->project }}
                        </td>

                        <td class="text-center">
                            {{ $wr->periode }}
                        </td>

                        <td class="text-center fw-semibold">
                            {{ $wr->items->count() }}
                        </td>

                        <td class="text-center">
                            <a href="{{ route('working-report.show', $wr->id) }}"
                               class="btn btn-sm btn-primary btnDetail">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="text-center text-muted py-4 small">
                            Belum ada Working Report
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>

{{-- ================= MARGIN ================= --}}

<div class="card shadow-sm border-0 mb-5">

    <div class="card-header bg-white">
        <div class="fw-semibold small">
            Summary
        </div>
    </div>

    @php
        $totalBeli = $poMasuk->poSuppliers->sum('grand_total');
        $totalPengeluaran = $poMasuk->pengeluaran->sum('amount');
        $margin = $poMasuk->total_jual - $totalBeli - $totalPengeluaran;
    @endphp

    <div class="card-body p-0">

        <table class="table table-bordered table-supplier mb-0">

            <tr>
                <th width="250">Nilai PO Klien</th>
                <td class="text-end fw-semibold">
                    Rp {{ number_format($poMasuk->total_jual,0,',','.') }}
                </td>
            </tr>

            <tr>
                <th>Nilai PO Kita</th>
                <td class="text-end fw-semibold">
                    Rp {{ number_format($totalBeli,0,',','.') }}
                </td>
            </tr>

            <tr>
                <th>Total Pengeluaran</th>
                <td class="text-end fw-semibold">
                    Rp {{ number_format($totalPengeluaran,0,',','.') }}
                </td>
            </tr>

            <tr class="table-light">
                <th>Margin</th>
                <td class="text-end fw-bold
                    {{ $margin > 0 ? 'text-success' : ($margin < 0 ? 'text-danger' : '') }}">
                    Rp {{ number_format($margin,0,',','.') }}
                </td>
            </tr>

        </table>

    </div>

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

{{-- SUCCESS MODAL --}}
@if(session('success'))
<div class="modal fade"
     id="successModal"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">

<div class="modal-body text-center py-4">

<i class="bi bi-check-circle-fill text-success"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Berhasil</h5>

<div class="text-muted mb-4">
    {{ session('success') }}
</div>

<button type="button"
        class="btn btn-primary px-4"
        data-bs-dismiss="modal">
    OK
</button>

</div>
</div>
</div>
</div>
@endif

<script>
document.addEventListener("DOMContentLoaded", function(){

    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    const loadingText = document.getElementById("loadingText");

    // ================= STATUS CHANGE =================
    const statusForm = document.getElementById("statusForm");

    if(statusForm){
        statusForm.querySelector("select").addEventListener("change", function(){

            loadingText.innerText = "Mengubah status...";
            loadingModal.show();

            setTimeout(()=>{
                statusForm.submit();
            },200);

        });
    }

    // ================= DETAIL BUTTON =================
    document.querySelectorAll('.btnDetail').forEach(btn=>{
        btn.addEventListener('click', function(e){

            e.preventDefault();

            loadingText.innerText = "Memuat detail...";
            loadingModal.show();

            setTimeout(()=>{
                window.location.href = this.href;
            },200);

        });
    });

    @if(session('success'))
    const successModal = new bootstrap.Modal(
        document.getElementById("successModal")
    );
    successModal.show();
@endif
});
</script>
@endsection

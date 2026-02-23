@extends('layouts.app')

@section('content')

<div class="container-fluid px-3">

    {{-- HEADER ACTION --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-semibold mb-0">Quotation Detail</h5>

        <div class="d-flex gap-2">
            <a href="{{ route('quotations.index') }}" 
               class="btn btn-secondary btn-sm">
               Kembali
            </a>

            <a href="#"
               id="btnPrintPdf"
               data-url="{{ route('quotations.print', $quotation->id) }}"
               class="btn btn-danger btn-sm"
               style="margin-left:7px;">
               Print PDF
            </a>
        </div>
    </div>

    {{-- HEADER INFO --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="info-label">To</div>
                    <div class="info-value">{{ $quotation->mitra_name ?? '-' }}</div>
                </div>

                <div class="col-md-6 text-md-end">
                    <div class="info-label">Quote No</div>
                    <div class="info-value fw-semibold">
                        {{ $quotation->quote_no }}
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="info-label">Vessel</div>
                    <div class="info-value">
                        {{ $quotation->vessel_name ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6 text-md-end">
                    <div class="info-label">Date</div>
                    <div class="info-value">
                        {{ $quotation->date 
                            ? \Carbon\Carbon::parse($quotation->date)->format('d M Y') 
                            : '-' }}
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <div class="info-label">Attention</div>
                <div class="info-value">
                    {{ $quotation->attention ?? '-' }}
                </div>
            </div>

            <div class="mb-2">
                <div class="info-label">Project</div>
                <div class="info-value">
                    {{ $quotation->project ?? '-' }}
                </div>
            </div>

            <div>
                <div class="info-label">Place</div>
                <div class="info-value">
                    {{ $quotation->place ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    {{-- ITEM TABLE --}}
    @php 
        $grandTotal = 0; 
        $no = 1;
    @endphp

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Item Details</strong>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0 item-table">
                <thead class="table-light text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th>Item</th>
                        <th width="15%">Price</th>
                        <th width="8%">Qty</th>
                        <th width="10%">Unit</th>
                        <th width="18%">Total</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($quotation->subItems as $sub)

                    <tr class="sub-row">
                        <td></td>
                        <td colspan="5">{{ $sub->name }}</td>
                    </tr>

                    @foreach($sub->items as $item)
                        @php $grandTotal += $item->total; @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $item->item }}</td>
                            <td class="text-end">
                                Rp {{ number_format($item->price,0,',','.') }}
                            </td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-center">{{ $item->unit }}</td>
                            <td class="text-end">
                                Rp {{ number_format($item->total,0,',','.') }}
                            </td>
                        </tr>
                    @endforeach

                @endforeach

                <tr class="total-row">
                    <td colspan="5" class="text-end fw-semibold">
                        TOTAL
                    </td>
                    <td class="text-end fw-bold">
                        Rp {{ number_format($grandTotal,0,',','.') }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- TERMS --}}
    @if($quotation->termsConditions->count())
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <strong>Terms & Conditions</strong>
            </div>
            <div class="card-body">
                <ol class="mb-0 small">
                    @foreach($quotation->termsConditions as $term)
                        <li class="mb-2">{{ $term->description }}</li>
                    @endforeach
                </ol>
            </div>
        </div>
    @endif

</div>

{{-- ================= PDF LOADING MODAL ================= --}}
<div class="modal fade"
     id="pdfModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-danger mb-3"
                     style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">
                    Membuat PDF quotation...
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-label{
    font-size:12px;
    font-weight:600;
    color:#6c757d;
}
.info-value{
    font-size:14px;
}
.item-table th,
.item-table td{
    font-size:13px;
    padding:8px 10px;
}
.sub-row{
    background:#f8f9fa;
    font-weight:600;
}
.total-row{
    background:#f1f3f5;
}
.table-hover tbody tr:hover{
    background:#f5f7fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const pdfModal = new bootstrap.Modal(
        document.getElementById('pdfModal')
    );

    const btnPrint = document.getElementById('btnPrintPdf');

    if(btnPrint){
        btnPrint.addEventListener('click', function (e) {

            e.preventDefault();

            pdfModal.show();

            const url = this.dataset.url;

            // redirect normal supaya tidak blank
            setTimeout(() => {
                window.location.href = url;
            }, 500);

        });
    }

});
</script>

@endsection
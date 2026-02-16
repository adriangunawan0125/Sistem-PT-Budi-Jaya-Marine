@extends('layouts.app')

@section('content')

<style>
.item-table{
    width:100%;
    border-collapse:collapse;
}

.item-table th,
.item-table td{
    border:1px solid #000;
    padding:6px;
    font-size:13px;
}

.item-table th{
    background:#b7c7d9;
    font-weight:bold;
    text-align:center;
}

.sub-row{
    font-weight:bold;
    background:#f8f9fa;
}

.total-row{
    font-weight:bold;
    background:#f1f1f1;
}

.money{
    width:100%;
    border-collapse:collapse;
}

.money td{
    border:none;
    padding:0;
}

.rp{ width:25px; }
.val{ text-align:right; }

.header-label{
    font-weight:600;
    color:#555;
    font-size:13px;
}
</style>

<div class="container">

    {{-- ================= HEADER ACTION ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Quotation Detail</h4>

        <div class="d-flex gap-2">
            <a href="{{ route('quotations.index') }}" 
               class="btn btn-secondary btn-sm">
               Kembali
            </a>

            <a href="{{ route('quotations.print', $quotation->id) }}" 
               target="_blank"
               class="btn btn-danger btn-sm">
               Print PDF
            </a>
        </div>
    </div>


    {{-- ================= HEADER INFO CARD ================= --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="header-label">To</div>
                    {{ $quotation->mitra_name ?? '-' }}
                </div>

                <div class="col-md-6 text-md-end">
                    <div class="header-label">Quote No</div>
                    {{ $quotation->quote_no }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="header-label">Vessel</div>
                    {{ $quotation->vessel_name ?? '-' }}
                </div>

                <div class="col-md-6 text-md-end">
                    <div class="header-label">Date</div>
                    {{ $quotation->date 
                        ? \Carbon\Carbon::parse($quotation->date)->format('d M Y') 
                        : '-' }}
                </div>
            </div>

            <div class="mb-2">
                <div class="header-label">Attention</div>
                {{ $quotation->attention ?? '-' }}
            </div>

            <div class="mb-2">
                <div class="header-label">Project</div>
                {{ $quotation->project ?? '-' }}
            </div>

            <div class="mb-2">
                <div class="header-label">Place</div>
                {{ $quotation->place ?? '-' }}
            </div>

        </div>
    </div>


    {{-- ================= ITEM CARD ================= --}}
    @php 
        $grandTotal = 0; 
        $no = 1;
    @endphp

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <strong>Item Details</strong>
        </div>

        <div class="card-body p-0">

            <table class="item-table">
                <thead>
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
                            <td>{{ $sub->name }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        @foreach($sub->items as $item)

                            @php $grandTotal += $item->total; @endphp

                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $item->item }}</td>

                                <td>
                                    <table class="money">
                                        <tr>
                                            <td class="rp">Rp</td>
                                            <td class="val">
                                                {{ number_format($item->price,0,',','.') }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td class="text-center">
                                    {{ $item->qty }}
                                </td>

                                <td class="text-center">
                                    {{ $item->unit }}
                                </td>

                                <td>
                                    <table class="money">
                                        <tr>
                                            <td class="rp">Rp</td>
                                            <td class="val">
                                                {{ number_format($item->total,0,',','.') }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        @endforeach

                    @endforeach

                    <tr class="total-row">
                        <td colspan="5" class="text-end">
                            TOTAL
                        </td>
                        <td>
                            <table class="money">
                                <tr>
                                    <td class="rp">Rp</td>
                                    <td class="val">
                                        {{ number_format($grandTotal,0,',','.') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>


    {{-- ================= TERMS CARD ================= --}}
    @if($quotation->termsConditions->count())
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <strong>Terms & Conditions</strong>
            </div>

            <div class="card-body">
                <ol class="mb-0">
                    @foreach($quotation->termsConditions as $term)
                        <li class="mb-2">
                            {{ $term->description }}
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    @endif

</div>
@endsection

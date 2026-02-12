@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Quotation Detail</h4>
        <a href="{{ route('quotations.index') }}" class="btn btn-secondary btn-sm">
            ← Back
        </a>
    </div>

    {{-- ================= HEADER ================= --}}
    <div class="card mb-4">
        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Quote No :</strong><br>
                    {{ $quotation->quote_no }}
                </div>
                <div class="col-md-6">
                    <strong>Date :</strong><br>
                    {{ $quotation->date ? \Carbon\Carbon::parse($quotation->date)->format('d-m-Y') : '-' }}
                </div>
            </div>

            <div class="mb-2">
                <strong>Mitra :</strong><br>
                {{ $quotation->mitra->nama_mitra ?? '-' }}
            </div>

            <div class="mb-2">
                <strong>Vessel :</strong><br>
                {{ $quotation->vessel->nama_vessel ?? '-' }}
            </div>

            <div class="mb-2">
                <strong>Attention :</strong><br>
                {{ $quotation->attention ?? '-' }}
            </div>

            <div class="mb-2">
                <strong>Project :</strong><br>
                {{ $quotation->project ?? '-' }}
            </div>

            <div class="mb-2">
                <strong>Place :</strong><br>
                {{ $quotation->place ?? '-' }}
            </div>

        </div>
    </div>

    {{-- ================= SUB ITEMS ================= --}}
    @php $grandTotal = 0; @endphp

    @forelse($quotation->subItems as $sub)

        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                <strong>{{ $sub->name }}</strong>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Unit</th>

                            @if(str_contains($sub->item_type,'day'))
                                <th>Day</th>
                            @endif

                            @if(str_contains($sub->item_type,'hour'))
                                <th>Hour</th>
                            @endif

                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php $subTotal = 0; @endphp

                        @forelse($sub->items as $item)

                            @php
                                $subTotal += $item->total;
                                $grandTotal += $item->total;
                            @endphp

                            <tr>
                                <td>{{ $item->item }}</td>
                                <td>Rp {{ number_format($item->price,0,',','.') }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ $item->unit }}</td>

                                @if(str_contains($sub->item_type,'day'))
                                    <td>{{ $item->day }}</td>
                                @endif

                                @if(str_contains($sub->item_type,'hour'))
                                    <td>{{ $item->hour }}</td>
                                @endif

                                <td>
                                    <strong>Rp {{ number_format($item->total,0,',','.') }}</strong>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    No items available
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="card-footer text-end">
                <strong>
                    Subtotal: Rp {{ number_format($subTotal,0,',','.') }}
                </strong>
            </div>
        </div>

    @empty
        <div class="alert alert-warning">
            No sub items found.
        </div>
    @endforelse


    {{-- ================= GRAND TOTAL ================= --}}
    <div class="card mb-4">
        <div class="card-body text-end">
            <h4>
                Grand Total :
                <strong>
                    Rp {{ number_format($grandTotal,0,',','.') }}
                </strong>
            </h4>
        </div>
    </div>


    {{-- ================= TERMS ================= --}}
    @if($quotation->termsConditions->isNotEmpty())
        <div class="card">
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

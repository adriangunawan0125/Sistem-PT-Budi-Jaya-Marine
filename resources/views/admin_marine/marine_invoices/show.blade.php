@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-primary">Marine Invoice</h1>
            <small class="text-muted fw-bold">PT. BUDIJAYA MARINE</small>
        </div>
        <div class="text-end">
            <h3 class="fw-bold">#{{ $marineInvoice->id }}</h3>
            <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($marineInvoice->invoice_date)->format('d M Y') }}</p>
        </div>
    </div>

    {{-- Company & Project Info --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            {{-- Company --}}
            <h6 class="fw-bold text-secondary">Company</h6>
            <p class="mb-1"><strong>Nama Perusahaan: </strong>{{ $marineInvoice->company->name }}</p>
            <p class="mb-2"><strong>Alamat: </strong>{{ $marineInvoice->company->address ?? '-' }}</p>

            {{-- Project --}}
            <h6 class="fw-bold text-secondary mt-3">Project</h6>
            <p><strong>{{ $marineInvoice->project ?? '-' }}</strong></p>

            {{-- Lainnya --}}
            <div class="row mt-3">
                <div class="col-md-4">
                    <p><strong>Authorization No: </strong>{{ $marineInvoice->authorization_no }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Vessel: </strong>{{ $marineInvoice->vessel ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>PO No: </strong>{{ $marineInvoice->po_no ?? '-' }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p><strong>Period: </strong>{{ \Carbon\Carbon::parse($marineInvoice->period)->format('M Y') }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Manpower: </strong>{{ $marineInvoice->manpower ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3 text-primary">Invoice Items</h5>
            <table class="table table-hover table-bordered">
                <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marineInvoice->items as $item)
                    <tr>
                        <td class="text-center"><strong>{{ $loop->iteration }}</strong></td>
                        <td><strong>{{ $item->description }}</strong></td>
                        <td class="text-center"><strong>{{ $item->qty }}</strong></td>
                        <td class="text-center"><strong>{{ $item->unit ?? '-' }}</strong></td>
                        <td class="text-end"><strong>{{ number_format($item->price, 0, ',', '.') }}</strong></td>
                        <td class="text-end"><strong>{{ number_format($item->amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Summary --}}
    <div class="row justify-content-end">
        <div class="col-md-4">
            <div class="card shadow border-success">
                <div class="card-body p-3">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="fw-bold text-secondary">Subtotal</th>
                            <td class="text-end"><strong>{{ number_format($marineInvoice->subtotal, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <th class="fw-bold text-secondary">DP</th>
                            <td class="text-end"><strong>{{ number_format($marineInvoice->dp, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr class="bg-success text-white fw-bold fs-5">
                            <th>Grand Total</th>
                            <td class="text-end fs-5"><strong>{{ number_format($marineInvoice->grand_total, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>Marine Invoices</h4>
        <a href="{{ route('marine-invoices.create') }}" class="btn btn-primary">
            + Create Invoice
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Company</th>
                <th>Project</th>
                <th>Vessel</th> {{-- ✅ GANTI SUBTOTAL --}}
                <th>Invoice Date</th>
                <th>Period</th>
                <th>Grand Total</th>
                <th width="180">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $invoice->company->name }}</td>
                <td>{{ $invoice->project ?? '-' }}</td>
                <td>{{ $invoice->vessel ?? '-' }}</td> {{-- ✅ VESSEL --}}
                <td>{{ $invoice->invoice_date }}</td>
                <td>{{ \Carbon\Carbon::parse($invoice->period)->format('M Y') }}</td>
                <td>{{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('marine-invoices.edit', $invoice->id) }}"
                       class="btn btn-sm btn-warning">
                       Edit
                    </a>

                    <a href="{{ route('marine-invoices.show', $invoice->id) }}"
                       class="btn btn-sm btn-info">
                       Detail
                    </a>

                    <a href="{{ route('marine-invoices.print', $invoice->id) }}"
                       class="btn btn-sm btn-dark"
                       target="_blank">
                       Print
                    </a>

                    <form action="{{ route('marine-invoices.destroy', $invoice->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Hapus invoice?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Data kosong</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

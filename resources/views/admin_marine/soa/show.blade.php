@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-semibold">Statement of Account</h5>

        <div class="d-flex gap-2">

              <a href="{{route('soa.index')}}" class="btn btn-sm btn-secondary" >
        Kembali</a>

         <a href="{{ route('soa.print', $soa->id) }}"
               target="_blank"
               class="btn btn-danger btn-sm" style="margin-left: 4px">
                Print
            </a>
            <a href="{{ route('soa.edit', $soa->id) }}" 
               class="btn btn-warning btn-sm" style="margin-left: 4px">
                Edit
            </a>

            <form action="{{ route('soa.destroy', $soa->id) }}" 
                  method="POST"
                  onsubmit="return confirm('Yakin hapus SOA ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" style="margin-left: 4px">
                    Hapus SOA
                </button>
            </form>

        </div>
    </div>

    {{-- ================= INFO CARD ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body small">

            <div class="row g-3">

                <div class="col-md-4 mb-3">
                    <div class="text-muted">Debtor</div>
                    <div class="fw-semibold">{{ $soa->debtor }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted">Statement Date</div>
                    <div class="fw-semibold">
                        {{ \Carbon\Carbon::parse($soa->statement_date)->format('d M Y') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted">Termin</div>
                    <div class="fw-semibold">{{ $soa->termin }}</div>
                </div>

                <div class="col-md-12">
                    <div class="text-muted">Address</div>
                    <div>{{ $soa->address }}</div>
                </div>

            </div>

        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0 align-middle">

                    <thead class="table-light small text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th>Customer</th>
                            <th>Vessel</th>
                            <th width="18%">Job Details</th>
                            <th>PO No</th>
                            <th>Invoice Date</th>
                            <th>Invoice No</th>
                            <th class="text-end">Amount</th>
                            <th>Accepted Date</th>
                            <th width="5%">Days</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody class="small">

                        @php 
                            $total = 0;
                            $statementDate = \Carbon\Carbon::parse($soa->statement_date);
                        @endphp

                        @foreach($soa->items as $i => $item)

                        @php
                            $invoice = $item->invoice;
                            $po = $invoice->poMasuk ?? null;

                            $days = $item->acceptment_date
                                ? \Carbon\Carbon::parse($item->acceptment_date)
                                    ->diffInDays($statementDate)
                                : 0;

                            $amount = $invoice->grand_total ?? 0;
                            $total += $amount;

                            $remark = $days > 30 ? 'OVER DUE' : ($item->remarks ?? '');
                        @endphp

                        <tr>
                            <td class="text-center">{{ $i+1 }}</td>

                            <td>{{ $po->mitra_marine ?? '-' }}</td>

                            <td class="text-center">
                                {{ $po->vessel ?? '-' }}
                            </td>

                            <td>
                                <div class="text-wrap">
                                    {{ $item->job_details ?? '-' }}
                                </div>
                            </td>

                            <td class="text-center">
                                {{ $po->no_po_klien ?? '-' }}
                            </td>

                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d/m/Y') }}
                            </td>

                            <td class="text-center">
                                {{ $invoice->no_invoice }}
                            </td>

                            <td class="text-end fw-semibold">
                                {{ number_format($amount,0,',','.') }}
                            </td>

                            <td class="text-center">
                                {{ $item->acceptment_date
                                    ? \Carbon\Carbon::parse($item->acceptment_date)->format('d/m/Y')
                                    : '-' }}
                            </td>

                            <td class="text-center">
                                @if($days > 30)
                                    <span class="text-danger fw-semibold">{{ $days }}</span>
                                @else
                                    {{ $days }}
                                @endif
                            </td>

                            <td>
                                @if($days > 30)
                                    <span class="text-danger fw-semibold">
                                        {{ $remark }}
                                    </span>
                                @else
                                    {{ $remark }}
                                @endif
                            </td>

                        </tr>

                        @endforeach

                        {{-- TOTAL --}}
                        <tr class="fw-bold">
                            <td colspan="7" class="text-end">
                                TOTAL
                            </td>

                            <td class="text-end">
                                {{ number_format($total,0,',','.') }}
                            </td>

                            <td colspan="3"></td>
                        </tr>

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@endsection

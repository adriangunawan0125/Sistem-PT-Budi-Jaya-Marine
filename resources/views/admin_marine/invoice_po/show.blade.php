@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Invoice</h4>

        <a href="{{ route('po-masuk.show', $invoicePo->po_masuk_id) }}"
           class="btn btn-secondary btn-sm">
            ← Kembali ke PO
        </a>
    </div>

    @php
        $colors = [
            'draft' => 'secondary',
            'issued' => 'primary',
            'paid' => 'success',
            'cancelled' => 'danger'
        ];
    @endphp

    {{-- ================= INFO CARD ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="row g-4">

                {{-- LEFT SIDE --}}
                <div class="col-md-8">

                    <div class="d-flex align-items-center mb-3" >
                        <h5 class="fw-bold mb-0" style="margin-right: 7px">
                            {{ $invoicePo->no_invoice }}
                        </h5>

                        <span class=" text-light badge bg-{{ $colors[$invoicePo->status] ?? 'secondary' }} ms-3 px-3 py-2">
                            {{ strtoupper($invoicePo->status) }}
                        </span>
                    </div>

                    <div class="row gy-3">

                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Tanggal</small>
                            <div>
                                {{ \Carbon\Carbon::parse($invoicePo->tanggal_invoice)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Periode</small>
                            <div>{{ $invoicePo->periode ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Authorization</small>
                            <div>{{ $invoicePo->authorization_no ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Vessel</small>
                            <div>{{ $invoicePo->poMasuk->vessel ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">PO No</small>
                            <div>{{ $invoicePo->poMasuk->no_po_klien ?? '-' }}</div>
                        </div>

                    </div>

                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-md-4">

                    {{-- STATUS DROPDOWN --}}
                    <form action="{{ route('invoice-po.update-status', $invoicePo->id) }}"
                          method="POST"
                          class="mb-4">
                        @csrf
                        @method('PATCH')

                        <label class="form-label small mb-1">
                            Ubah Status
                        </label>

                        <select name="status"
                                class="form-control form-control-sm"
                                onchange="this.form.submit()">

                            <option value="draft"
                                {{ $invoicePo->status == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="issued"
                                {{ $invoicePo->status == 'issued' ? 'selected' : '' }}>
                                Issued
                            </option>

                            <option value="paid"
                                {{ $invoicePo->status == 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>

                            <option value="cancelled"
                                {{ $invoicePo->status == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>
                    </form>

                    {{-- ACTION BUTTONS --}}
                   <div class="d-flex flex-column gap-2">

    <a href="{{ route('invoice-po.print', $invoicePo->id) }}"
       target="_blank"
       class="btn btn-danger btn-sm w-100 mb-1">
        Print Invoice
    </a>

    <a href="{{ route('invoice-po.edit', $invoicePo->id) }}"
       class="btn btn-warning btn-sm w-100 mb-1">
        Edit Invoice
    </a>

    <form action="{{ route('invoice-po.destroy', $invoicePo->id) }}"
          method="POST"
          class="w-100"
          onsubmit="return confirm('Yakin ingin menghapus invoice ini?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="btn btn-danger btn-sm w-100">
            Hapus Invoice
        </button>
    </form>

</div>


                </div>

            </div>

        </div>
    </div>


    {{-- ================= ITEM TABLE ================= --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Description</th>
                            <th width="80">Qty</th>
                            <th width="100">Unit</th>
                            <th width="150">Price</th>
                            <th width="150">Amount</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($invoicePo->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-center">{{ $item->unit }}</td>
                            <td class="text-end">
                                Rp {{ number_format($item->price,0,',','.') }}
                            </td>
                            <td class="text-end fw-semibold">
                                Rp {{ number_format($item->amount,0,',','.') }}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>

        {{-- ================= SUMMARY ================= --}}
        <div class="card-footer bg-white">
            <div class="row justify-content-end">
                <div class="col-md-4">

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>
                            Rp {{ number_format($invoicePo->subtotal,0,',','.') }}
                        </span>
                    </div>

                    @if($invoicePo->discount_amount > 0)
                    <div class="d-flex justify-content-between text-danger mb-2">
                        <span>Discount</span>
                        <span>
                            - Rp {{ number_format($invoicePo->discount_amount,0,',','.') }}
                        </span>
                    </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span>Grand Total</span>
                        <span>
                            Rp {{ number_format($invoicePo->grand_total,0,',','.') }}
                        </span>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection

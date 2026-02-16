@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Detail Pengeluaran</h4>

        <div class="d-flex gap-2">

            <a href="{{ route('pengeluaran-po.edit',$pengeluaranPo->id) }}"
               class="btn btn-warning btn-sm">
                Edit
            </a>

            <form action="{{ route('pengeluaran-po.destroy',$pengeluaranPo->id) }}"
                  method="POST"
                  onsubmit="return confirm('Hapus pengeluaran ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">
                    Hapus
                </button>
            </form>

            <a href="{{ route('po-masuk.show',$pengeluaranPo->po_masuk_id) }}"
               class="btn btn-secondary btn-sm">
                ← Kembali
            </a>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="mb-3">
                <strong>PO Klien:</strong><br>
                {{ $pengeluaranPo->poMasuk->no_po_klien ?? '-' }}
            </div>

            <div class="mb-3">
                <strong>Item:</strong><br>
                {{ $pengeluaranPo->item }}
            </div>

            <div class="mb-3">
                <strong>Qty:</strong><br>
                {{ $pengeluaranPo->qty }}
            </div>

            <div class="mb-3">
                <strong>Harga:</strong><br>
                Rp {{ number_format($pengeluaranPo->price,0,',','.') }}
            </div>

            <div class="mb-3">
                <strong>Total Amount:</strong><br>
                <span class="fw-bold text-danger">
                    Rp {{ number_format($pengeluaranPo->amount,0,',','.') }}
                </span>
            </div>

        </div>
    </div>

</div>
@endsection

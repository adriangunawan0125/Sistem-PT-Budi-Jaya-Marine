@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Pengeluaran</h4>

        <div class="d-flex gap-2">

            <a href="{{ route('pengeluaran-po.edit',$pengeluaranPo->id) }}"
               class="btn btn-warning btn-sm px-3" style="margin-left: 4px">
                Edit
            </a>

            <form action="{{ route('pengeluaran-po.destroy',$pengeluaranPo->id) }}"
                  method="POST"
                  onsubmit="return confirm('Hapus pengeluaran ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm px-3" style="margin-left: 4px">
                    Hapus
                </button>
            </form>

            <a href="{{ route('po-masuk.show',$pengeluaranPo->po_masuk_id) }}"
               class="btn btn-secondary btn-sm px-3" style="margin-left: 4px">
                ← Kembali
            </a>

        </div>
    </div>

    {{-- ================= DETAIL CARD ================= --}}
    <div class="card shadow-sm">
        <div class="card-body px-4 py-4">

            <div class="row gy-4">

                {{-- PO KLIEN --}}
                <div class="col-md-6 mb-3">
                    <small class="text-muted">PO Klien</small>
                    <div class="fw-semibold">
                        {{ $pengeluaranPo->poMasuk->no_po_klien ?? '-' }}
                    </div>
                </div>

                {{-- ITEM --}}
                <div class="col-md-6 mb-3">
                    <small class="text-muted">Nama Pengeluaran</small>
                    <div class="fw-semibold">
                        {{ $pengeluaranPo->item }}
                    </div>
                </div>

                {{-- QTY --}}
                <div class="col-md-6">
                    <small class="text-muted">Qty</small>
                    <div class="fw-semibold">
                        {{ $pengeluaranPo->qty }}
                    </div>
                </div>

                {{-- HARGA --}}
                <div class="col-md-6">
                    <small class="text-muted">Harga</small>
                    <div class="fw-semibold">
                        Rp {{ number_format($pengeluaranPo->price,0,',','.') }}
                    </div>
                </div>

                {{-- TOTAL --}}
                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12 text-end">
                    <small class="text-muted">Total Amount</small>
                    <h4 class="fw-bold text-danger mt-1">
                        Rp {{ number_format($pengeluaranPo->amount,0,',','.') }}
                    </h4>
                </div>

                {{-- ================= BUKTI GAMBAR ================= --}}
                <div class="col-12 mt-4">
                    <hr>
                    <small class="text-muted">Bukti Pengeluaran</small>

                    <div class="mt-2">
                        @if($pengeluaranPo->bukti_gambar)
                            <a href="{{ asset('storage/'.$pengeluaranPo->bukti_gambar) }}" target="_blank">
                                <img src="{{ asset('storage/'.$pengeluaranPo->bukti_gambar) }}"
                                     class="img-thumbnail"
                                     style="max-height:250px;">
                            </a>
                            <div class="small text-muted mt-1">
                                Klik gambar untuk melihat ukuran penuh
                            </div>
                        @else
                            <div class="text-muted fst-italic">
                                Tidak ada bukti pengeluaran
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
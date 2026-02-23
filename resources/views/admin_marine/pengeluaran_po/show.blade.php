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

           <button type="button"
        class="btn btn-danger btn-sm px-3"
        data-bs-toggle="modal"
        data-bs-target="#deleteModal"
        style="margin-left: 4px">
    Hapus
</button>

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
{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-danger"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Hapus Pengeluaran?</h5>

<p class="text-muted">
Data pengeluaran ini akan dihapus permanen.
</p>

<form action="{{ route('pengeluaran-po.destroy',$pengeluaranPo->id) }}"
      method="POST">
@csrf
@method('DELETE')

<div class="d-flex justify-content-center gap-2 mt-3">
<button type="button"
        class="btn btn-secondary"
        data-bs-dismiss="modal" style="margin-right: 4px">
Batal
</button>

<button type="submit"
        class="btn btn-danger">
Hapus
</button>
</div>

</form>

</div>
</div>
</div>
</div>
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
        class="btn btn-success px-4"
        data-bs-dismiss="modal">
    OK
</button>

</div>
</div>
</div>
</div>
@endif

@if(session('success'))
<script>
document.addEventListener("DOMContentLoaded", function(){

    const successModal = new bootstrap.Modal(
        document.getElementById("successModal")
    );

    successModal.show();

});
</script>
@endif

@endsection
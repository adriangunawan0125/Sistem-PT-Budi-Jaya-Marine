@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-semibold">Detail Working Report</h5>

        <div class="d-flex gap-2">

            <a href="{{ route('po-masuk.show', $workingReport->poMasuk->id) }}"
               class="btn btn-sm btn-secondary">
                Kembali
            </a>

            <button type="button"
                    id="btnPrintPdf"
                    class="btn btn-sm btn-danger" style="margin-left: 4px">
                Print PDF
            </button>

            <a href="{{ route('working-report.edit', $workingReport->id) }}"
               class="btn btn-sm btn-warning" style="margin-left: 4px">
                Edit
            </a>

            <button type="button"
                    class="btn btn-sm btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteModal" style="margin-left: 4px">
                Hapus
            </button>

        </div>
    </div>

    {{-- ================= INFO CARD ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body small">

            <div class="row g-3">

                <div class="col-md-4">
                    <div class="text-muted">Company</div>
                    <div class="fw-semibold">
                        {{ $workingReport->poMasuk->mitra_marine }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted">Project</div>
                    <div class="fw-semibold">
                        {{ $workingReport->project }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted">Vessel</div>
                    <div>
                        {{ $workingReport->poMasuk->vessel }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted">Place</div>
                    <div>
                        {{ $workingReport->place ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted">PO No</div>
                    <div>
                        {{ $workingReport->poMasuk->no_po_klien }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted">Periode</div>
                    <div>
                        {{ $workingReport->periode }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- ================= ITEMS ================= --}}
    @forelse($workingReport->items as $item)

    <div class="card shadow-sm mb-4">
        <div class="card-header small fw-semibold">
            Tanggal : {{ \Carbon\Carbon::parse($item->work_date)->format('d M Y') }}
        </div>

        <div class="card-body small">

            {{-- DETAIL --}}
            <div class="mb-3">
                <div class="text-muted mb-1">Detail Pekerjaan</div>
                <div class="border rounded p-3 bg-light"
                     style="white-space: pre-line;">
                    {{ $item->detail }}
                </div>
            </div>

            {{-- IMAGES --}}
            @if($item->images->count())
            <div>
                <div class="text-muted mb-2">Dokumentasi</div>

                <div class="row g-3">
                    @foreach($item->images as $image)
                        <div class="col-md-3">
                            <div class="border rounded p-2 bg-white text-center shadow-sm">
                                <img src="{{ asset('storage/'.$image->image_path) }}"
                                     class="img-fluid rounded"
                                     style="max-height:160px; object-fit:cover;">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    @empty
        <div class="card shadow-sm">
            <div class="card-body text-center text-muted small">
                Belum ada item working report
            </div>
        </div>
    @endforelse

</div>

{{-- ================= DELETE MODAL ================= --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">

<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-danger"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Hapus Working Report?</h5>

<p class="text-muted">
Data tidak bisa dikembalikan setelah dihapus.
</p>

<form action="{{ route('working-report.destroy', $workingReport->id) }}"
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

{{-- ================= PDF LOADING MODAL ================= --}}
<div class="modal fade"
     id="pdfLoadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<div class="spinner-border text-danger mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Membuat PDF...
</div>

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

<script>
document.addEventListener("DOMContentLoaded", function(){

    const btnPrint = document.getElementById("btnPrintPdf");
    const pdfModal = new bootstrap.Modal(
        document.getElementById("pdfLoadingModal")
    );

    if(btnPrint){
        btnPrint.addEventListener("click", function(){

            pdfModal.show();

            setTimeout(function(){
                window.open(
                    "{{ route('working-report.print', $workingReport->id) }}",
                    "_blank"
                );
                pdfModal.hide();
            }, 400);

        });
    }

    // ================= SUCCESS MODAL =================
    @if(session('success'))
        const successModal = new bootstrap.Modal(
            document.getElementById("successModal")
        );
        successModal.show();
    @endif

});
</script>

@endsection
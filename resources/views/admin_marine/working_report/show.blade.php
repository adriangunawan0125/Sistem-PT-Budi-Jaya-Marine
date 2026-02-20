@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-semibold">Detail Working Report</h5>

        <div class="d-flex gap-2">

            
            <a href="{{ route('po-masuk.show', $workingReport->poMasuk->id) }}"
               class="btn btn-sm btn-secondary">
                kembali
            </a>
            <a href="{{ route('working-report.print', $workingReport->id) }}"
               style="margin-left: 4px" class="btn btn-sm btn-danger"
               target="_blank">
                Print pdf
            </a>
            <a href="{{ route('working-report.edit', $workingReport->id) }}"
               class="btn btn-sm btn-warning" style="margin-left: 4px">
                Edit
            </a>


            <form action="{{ route('working-report.destroy', $workingReport->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus working report ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" style="margin-left: 4px">
                    Hapus
                </button>
            </form>

        </div>
    </div>


    {{-- ================= INFO CARD ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body small">

            <div class="row g-3">

                <div class="col-md-4 mb-3">
                    <div class="text-muted ">Company</div>
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
           tanggal : {{ \Carbon\Carbon::parse($item->work_date)->format('d M Y') }}
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
@endsection

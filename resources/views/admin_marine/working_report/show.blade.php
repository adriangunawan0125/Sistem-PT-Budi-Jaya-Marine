@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Working Report</h4>

        <div class="d-flex gap-2">

            <a href="{{ route('working-report.edit', $workingReport->id) }}"
               class="btn btn-warning btn-sm">
                Edit
            </a>

            <a href="{{ route('working-report.print', $workingReport->id) }}"
               class="btn btn-info btn-sm"
               target="_blank">
                Print
            </a>

            <form action="{{ route('working-report.destroy', $workingReport->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus working report ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">
                    Hapus
                </button>
            </form>

        </div>
    </div>


    {{-- ================= INFO CARD ================= --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Company</strong><br>
                    {{ $workingReport->poMasuk->mitra_marine }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Project</strong><br>
                    {{ $workingReport->project }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Vessel</strong><br>
                    {{ $workingReport->poMasuk->vessel }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Place</strong><br>
                    {{ $workingReport->place ?? '-' }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>PO No</strong><br>
                    {{ $workingReport->poMasuk->no_po_klien }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Periode</strong><br>
                    {{ $workingReport->periode }}
                </div>

            </div>

        </div>
    </div>


    {{-- ================= ITEMS ================= --}}
    @forelse($workingReport->items as $item)

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <strong>
                Tanggal :
                {{ \Carbon\Carbon::parse($item->work_date)->format('d M Y') }}
            </strong>
        </div>

        <div class="card-body">

            {{-- DETAIL --}}
            <div class="mb-3">
                <strong>Detail Pekerjaan :</strong>
                <div style="white-space: pre-line;">
                    {{ $item->detail }}
                </div>
            </div>

            {{-- IMAGES --}}
            @if($item->images->count() > 0)
            <div>
                <strong>Dokumentasi :</strong>

                <div class="row mt-2">
                    @foreach($item->images as $image)
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-2 text-center">
                                <img src="{{ asset('storage/'.$image->image_path) }}"
                                     class="img-fluid rounded"
                                     style="max-height:180px;">
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
            <div class="card-body text-center text-muted">
                Belum ada item working report
            </div>
        </div>
    @endforelse


</div>
@endsection

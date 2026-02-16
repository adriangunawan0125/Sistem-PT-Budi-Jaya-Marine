@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Daftar Timesheet</h4>

        @if(isset($poMasuk))
            <a href="{{ route('timesheet.create', $poMasuk->id) }}"
               class="btn btn-success btn-sm">
                + Buat Timesheet
            </a>
        @endif
    </div>

    {{-- ================= INFO PO ================= --}}
    @if(isset($poMasuk))
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Company :</strong><br>
                    {{ $poMasuk->mitra_marine }}
                </div>
                <div class="col-md-4">
                    <strong>Vessel :</strong><br>
                    {{ $poMasuk->vessel }}
                </div>
                <div class="col-md-4">
                    <strong>PO No :</strong><br>
                    {{ $poMasuk->no_po_klien }}
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ================= TABLE ================= --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Project</th>
                        <th width="140">Manpower</th>
                        <th width="120">Total Days</th>
                        <th width="140">Total Hours</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($timesheets as $index => $timesheet)
                    @php
                        $totalHours = $timesheet->items->sum('hours');
                        $totalDays  = $timesheet->items->count();
                    @endphp

                    <tr>
                        <td class="text-center">{{ $index+1 }}</td>

                        <td>
                            <strong>{{ $timesheet->project }}</strong><br>
                            <small class="text-muted">
                                PO: {{ $timesheet->poMasuk->no_po_klien }}
                            </small>
                        </td>

                        <td class="text-center">
                            {{ $timesheet->manpower }}
                        </td>

                        <td class="text-center">
                            {{ $totalDays }}
                        </td>

                        <td class="text-center fw-semibold">
                            {{ number_format($totalHours,2) }} Jam
                        </td>

                        <td>
                            <a href="{{ route('timesheet.show', $timesheet->id) }}"
                               class="btn btn-sm btn-primary">
                                Detail
                            </a>

                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted p-3">
                            Belum ada data timesheet
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection

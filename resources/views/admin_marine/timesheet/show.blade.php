@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h4 class="mb-0">
            Detail Timesheet
        </h4>

        <a href="{{ route('po-masuk.show', $timesheet->po_masuk_id) }}"
           class="btn btn-outline-secondary btn-sm">
            ← Kembali ke PO
        </a>
    </div>

    @php
        $colors = [
            'draft' => 'secondary',
            'approved' => 'primary',
            'completed' => 'success'
        ];
    @endphp

    {{-- ================= INFO CARD ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="row">

                {{-- LEFT SIDE --}}
                <div class="col-md-8">

                    <h5 class="fw-bold mb-3">
                        {{ $timesheet->project }}

                        <span class=" text-light badge bg-{{ $colors[$timesheet->status] ?? 'secondary' }} ms-2">
                            {{ strtoupper($timesheet->status) }}
                        </span>
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Company</small>
                            <div>{{ $timesheet->poMasuk->mitra_marine }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Vessel</small>
                            <div>{{ $timesheet->poMasuk->vessel }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">PO No</small>
                            <div>{{ $timesheet->poMasuk->no_po_klien }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Manpower</small>
                            <div>{{ $timesheet->manpower }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Total Hours</small>
                            <div class="fw-bold">
                                {{ number_format($timesheet->total_hours,2) }} Jam
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-md-4">

                    <div class="d-flex flex-column gap-2">

                        <a href="{{ route('timesheet.print', $timesheet->id) }}"
                           target="_blank"
                           class="btn btn-dark btn-sm">
                            Print
                        </a>

                        <a href="{{ route('timesheet.edit', $timesheet->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('timesheet.destroy', $timesheet->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus timesheet ini?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm w-100">
                                Hapus
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

            <table class="table table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="120">Date</th>
                        <th width="100">Day</th>
                        <th width="120">Time</th>
                        <th width="80">Hours</th>
                        <th width="150">Manpower</th>
                        <th>Kind of Work</th>
                    </tr>
                </thead>
                <tbody>

@forelse($timesheet->items as $index => $item)
<tr>

    <td class="text-center" style="vertical-align: top;">
        {{ $index+1 }}
    </td>

    <td class="text-center" style="vertical-align: top;">
        {{ \Carbon\Carbon::parse($item->work_date)->format('d M Y') }}
    </td>

    <td class="text-center" style="vertical-align: top;">
        {{ $item->day }}
    </td>

    <td class="text-center" style="vertical-align: top;">
        {{ \Carbon\Carbon::parse($item->time_start)->format('H:i') }}
        -
        {{ \Carbon\Carbon::parse($item->time_end)->format('H:i') }}
    </td>

    <td class="text-center fw-semibold" style="vertical-align: top;">
        {{ number_format($item->hours,2) }}
    </td>

    <td class="text-center" style="vertical-align: top;">
        {{ $item->manpower ?? '-' }}
    </td>

   <td style="vertical-align: top;">
    {!! nl2br(e(trim($item->kind_of_work))) !!}
</td>


</tr>

@empty
<tr>
    <td colspan="7" class="text-center text-muted">
        Belum ada data timesheet
    </td>
</tr>
@endforelse

</tbody>

            </table>

        </div>

        <div class="card-footer bg-white">
            <div class="row justify-content-end">
                <div class="col-md-4">

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span>Total Hours</span>
                        <span>{{ number_format($timesheet->total_hours,2) }} Jam</span>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection

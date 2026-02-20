@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Timesheet</h4>

        <a href="{{ route('po-masuk.show', $timesheet->po_masuk_id) }}"
           class="btn btn-secondary btn-sm">
            Kembali
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

            <div class="row g-4">

                {{-- LEFT SIDE --}}
                <div class="col-md-8">

                    <h5 class="fw-bold mb-4">
                        {{ $timesheet->project }}

                        <span class=" text-light badge bg-{{ $colors[$timesheet->status] ?? 'secondary' }} ms-2">
                            {{ strtoupper($timesheet->status) }}
                        </span>
                    </h5>

                    <div class="row g-3">

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Company</small>
                            <div class="fw-semibold">
                                {{ $timesheet->poMasuk->mitra_marine }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Vessel</small>
                            <div class="fw-semibold">
                                {{ $timesheet->poMasuk->vessel }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">PO No</small>
                            <div>
                                {{ $timesheet->poMasuk->no_po_klien }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Manpower</small>
                            <div>
                                {{ $timesheet->manpower }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Total Hours</small>
                            <div class="fw-bold fs-5 text-primary">
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
           class="btn btn-danger btn-sm w-100 mb-1">
            Print
        </a>

        <a href="{{ route('timesheet.edit', $timesheet->id) }}"
           class="btn btn-warning btn-sm w-100 mb-1">
            Edit
        </a>

        <form action="{{ route('timesheet.destroy', $timesheet->id) }}"
              method="POST"
              class="w-100"
              onsubmit="return confirm('Yakin ingin menghapus timesheet ini?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-danger btn-sm w-100">
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

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="50">No</th>
                            <th width="130">Date</th>
                            <th width="110">Day</th>
                            <th width="150">Time</th>
                            <th width="90">Hours</th>
                            <th width="160">Manpower</th>
                            <th>Kind of Work</th>
                        </tr>
                    </thead>
                    <tbody>

                    @forelse($timesheet->items as $index => $item)
                    <tr>

                        <td class="text-center">
                            {{ $index+1 }}
                        </td>

                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($item->work_date)->format('d M Y') }}
                        </td>

                        <td class="text-center">
                            {{ $item->day }}
                        </td>

                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($item->time_start)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($item->time_end)->format('H:i') }}
                        </td>

                        <td class="text-center fw-semibold text-primary">
                            {{ number_format($item->hours,2) }}
                        </td>

                        <td class="text-center">
                            {{ $item->manpower ?? '-' }}
                        </td>

                        <td>
                            {!! nl2br(e(trim($item->kind_of_work))) !!}
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada data timesheet
                        </td>
                    </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>

        </div>

        <div class="card-footer bg-white">
            <div class="row justify-content-end">
                <div class="col-md-4">

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span>Total Hours</span>
                        <span class="text-primary">
                            {{ number_format($timesheet->total_hours,2) }} Jam
                        </span>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection

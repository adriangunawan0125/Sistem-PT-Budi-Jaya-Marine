@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Daftar Statement of Account (SOA)</h4>

 <a href="{{ route('soa.create', $poMasuk->id) }}"
   class="btn btn-success btn-sm">
    + Buat SOA
</a>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Debtor</th>
                        <th width="140">Statement Date</th>
                        <th width="120">Termin</th>
                        <th width="160">Total Invoice</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($soas as $index => $soa)

                    @php
                        $totalInvoice = $soa->items->sum(function($item){
                            return $item->invoice->grand_total ?? 0;
                        });
                    @endphp

                    <tr>
                        <td class="text-center">{{ $index+1 }}</td>

                        <td>
                            <strong>{{ $soa->debtor }}</strong><br>
                            <small class="text-muted">
                                {{ $soa->address }}
                            </small>
                        </td>

                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($soa->statement_date)->format('d M Y') }}
                        </td>

                        <td class="text-center">
                            {{ $soa->termin }}
                        </td>

                        <td class="text-end fw-bold">
                            Rp {{ number_format($totalInvoice,0,',','.') }}
                        </td>

                        <td>

                            <a href="{{ route('soa.show', $soa->id) }}"
                               class="btn btn-sm btn-info">
                                Detail
                            </a>

                            <a href="{{ route('soa.edit', $soa->id) }}"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <a href="{{ route('soa.print', $soa->id) }}"
                               target="_blank"
                               class="btn btn-sm btn-primary">
                                Print
                            </a>

                            <form action="{{ route('soa.destroy', $soa->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus SOA ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted p-4">
                            Belum ada data SOA
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection

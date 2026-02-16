@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================= PAGE TITLE ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Daftar Pengeluaran PO</h4>

        <a href="{{ route('po-masuk.index') }}"
           class="btn btn-secondary btn-sm">
            ← Kembali ke PO
        </a>
    </div>

    {{-- ================= SEARCH ================= --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body">

            <form method="GET" action="{{ route('pengeluaran-po.index') }}">
                <div class="row g-2">

                    <div class="col-md-10">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Cari berdasarkan item atau No PO Klien...">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            Search
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th>No PO Klien</th>
                            <th>Item</th>
                            <th width="80">Qty</th>
                            <th width="120">Harga</th>
                            <th width="150">Amount</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($pengeluaran as $index => $exp)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <strong>
                                    {{ $exp->poMasuk->no_po_klien ?? '-' }}
                                </strong>
                            </td>

                            <td>{{ $exp->item }}</td>

                            <td>{{ $exp->qty }}</td>

                            <td>
                                Rp {{ number_format($exp->price,0,',','.') }}
                            </td>

                            <td class="fw-bold">
                                Rp {{ number_format($exp->amount,0,',','.') }}
                            </td>

                            <td>
                                <a href="{{ route('po-masuk.show',$exp->po_masuk_id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail PO
                                </a>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="7"
                                class="text-center text-muted p-4">
                                Belum ada data pengeluaran
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Laporan Pemasukan Harian</h4>

    {{-- INFO TOTAL --}}
    <div class="alert alert-info mb-3">
        Total Pemasukan:
        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
    </div>

    {{-- FILTER --}}
    <form method="GET"
          action="{{ route('pemasukan.laporan.harian') }}"
          class="mb-3"
          id="filterForm">

        <input type="date"
               name="tanggal"
               value="{{ $tanggal }}"
               class="form-control d-inline-block me-2 mb-2"
               style="width:180px">

        <button type="submit"
                class="btn btn-primary me-2 mb-2"
                id="btnFilter">
            Filter
        </button>

        <a href="{{ route('pemasukan.print.harian', ['tanggal' => $tanggal]) }}"
           class="btn btn-success me-2 mb-2"
           id="btnPrint"
           data-url="{{ route('pemasukan.print.harian', ['tanggal' => $tanggal]) }}">
            Print PDF
        </a>

        <a href="{{ route('pemasukan.index') }}"
           class="btn btn-secondary me-2 mb-2">
            Kembali
        </a>
    </form>

    {{-- TABLE --}}
    <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
            <tr>
                <th width="50">No</th>
                <th>Tanggal</th>
                <th>Mitra</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pemasukan as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                    </td>
                    <td>{{ $item->mitra->nama_mitra ?? '-' }}</td>
                    <td class="text-center">{{ ucfirst($item->kategori) }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>
                        <b>Rp {{ number_format($item->nominal, 0, ',', '.') }}</b>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Data pemasukan tidak ditemukan
                    </td>
                </tr>
            @endforelse

            <tr>
                <th colspan="5" class="text-end">TOTAL</th>
                <th>Rp {{ number_format($total,0,',','.') }}</th>
            </tr>
        </tbody>
    </table>

</div>

{{-- ================= LOADING MODAL ================= --}}
<div class="modal fade"
     id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3"
                     style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold" id="loadingText">
                    Memuat data...
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const loadingText = document.getElementById('loadingText');

    // FILTER → submit normal + modal
    document.getElementById('btnFilter').addEventListener('click', function () {
        loadingText.innerText = 'Memuat data...';
        modal.show();
    });

    // PRINT PDF
    document.getElementById('btnPrint').addEventListener('click', function (e) {
        e.preventDefault();

        loadingText.innerText = 'Membuat PDF...';
        modal.show();

        const url = this.dataset.url;

        setTimeout(() => {
            window.open(url, '_blank');
            modal.hide();
        }, 700);
    });

});
</script>
@endsection

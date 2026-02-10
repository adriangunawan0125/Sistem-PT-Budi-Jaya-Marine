@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">
        Laporan Pengeluaran Internal - Bulan 
        {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}
    </h4>

<form method="GET"
      action="{{ route('pengeluaran_internal.laporan') }}"
      class="mb-3 d-flex align-items-center gap-2 flex-wrap"
      id="filterForm">

    <input type="month"
           name="bulan"
           value="{{ $bulan }}"
           class="form-control w-auto" style="margin-right:4px">

    <button class="btn btn-primary" id="btnFilter" style="margin-right:5px">Tampilkan</button>
    
    <a style="margin-right:5px" href="{{ route('pengeluaran_internal.pdf', ['bulan' => $bulan]) }}"
       target="_blank"
       class="btn btn-danger"
       id="btnPrint"
       data-url="{{ route('pengeluaran_internal.pdf', ['bulan' => $bulan]) }}">
       Print PDF
    </a>

    <a href="{{ route('pengeluaran_internal.index') }}"
       class="btn btn-secondary">
       Kembali
    </a>
</form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengeluaran as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3"><b>Total</b></td>
                <td><b>Rp {{ number_format($total,0,',','.') }}</b></td>
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

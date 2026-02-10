@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Detail Pengeluaran Transport</h4>

    {{-- INFO UTAMA --}}
    <div class="card mb-3">
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="200">Tanggal</th>
                    <td>: {{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Unit</th>
                    <td>: {{ $pengeluaran->unit->nama_unit ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Total Nominal</th>
                    <td>
                        : <strong>
                            Rp {{ number_format($pengeluaran->total_amount, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- RINCIAN ITEM --}}
    <h5>Rincian Item Pengeluaran</h5>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Keterangan</th>
                <th width="20%">Nominal</th>
                <th width="15%">Detail</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluaran->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                    <td>
                        <a href="{{ route('pengeluaran_transport.item_detail', $item->id) }}"
                           class="btn btn-sm btn-info btn-loading">
                           Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Tidak ada item pengeluaran
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-end">Total</th>
                <th colspan="2">
                    Rp {{ number_format(
                        $pengeluaran->items->sum('nominal'),
                        0,
                        ',',
                        '.'
                    ) }}
                </th>
            </tr>
        </tfoot>
    </table>

    <a href="{{ route('pengeluaran_transport.rekap') }}" class="btn btn-secondary">
        Kembali
    </a>

</div>
{{-- MODAL LOADING --}}
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <h5 class="mb-0">Memuat data...</h5>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    let modalEl = document.getElementById('loadingModal');
    if (!modalEl) return;

    let loadingModal = new bootstrap.Modal(modalEl);

    // FILTER (kalau ada)
    let filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function () {
            loadingModal.show();
        });
    }

    // SEMUA LINK PINDAH HALAMAN
    document.querySelectorAll('.btn-loading').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();

            let url = this.getAttribute('href');
            if (!url) return;

            loadingModal.show();

            setTimeout(function(){
                window.location.href = url;
            }, 350);
        });
    });

});
</script>

@endsection

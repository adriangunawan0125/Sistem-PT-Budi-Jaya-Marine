@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Pengeluaran Transport</h4>

{{-- ALERT --}}
   @if (session('success'))
    <input type="hidden" id="success-message" value="{{ session('success') }}">
@endif

    {{-- Filter Bulan & Tombol --}}
    <form id="filterForm" method="GET" class="mb-3">
        <input type="month"
               name="bulan"
               value="{{ $bulan }}"
               class="form-control w-auto d-inline">

        {{-- SEARCH DESKRIPSI --}}
        <input type="text"
               name="search"
               value="{{ $search ?? '' }}"
               class="form-control w-auto d-inline"
               placeholder="Cari keterangan">

        <button type="submit" class="btn btn-primary">
            Filter
        </button>

        <a href="{{ route('pengeluaran_transport.create') }}"
           class="btn btn-success">
            Tambah Pengeluaran
        </a>

        <a href="{{ route('pengeluaran_transport.laporan',['bulan'=>$bulan]) }}"
           class="btn btn-info" id="btnLaporan">
            Laporan Bulanan
        </a>
    </form>

 
        {{-- ================= TABEL PENGELUARAN TRANSPORT ================= --}}
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Plat Nomor</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Nominal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transport as $index => $t)
            @foreach($t->items as $itemIndex => $item)
                <tr>
                    @if($itemIndex == 0)
                        <td rowspan="{{ $t->items->count() }}">{{ $index + 1 }}</td>
                        <td rowspan="{{ $t->items->count() }}">{{ $t->unit->nama_unit }}</td>
                        <td rowspan="{{ $t->items->count() }}">{{ \Carbon\Carbon::parse($t->tanggal)->format('d-m-Y') }}</td>
                    @endif

                    <td>{{ $item->keterangan }}</td>
                    <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>

                    @if($itemIndex == 0)
                        <td rowspan="{{ $t->items->count() }}">
                            <a href="{{ route('pengeluaran_transport.show', $t->id) }}"
   class="btn btn-info btn-sm mb-1">
    Detail
</a>

                            <a href="{{ route('pengeluaran_transport.edit', $t->id) }}"
                               class="btn btn-warning btn-sm mb-1">
                                Edit
                            </a>

                            {{-- Tombol hapus modal --}}
                            <button type="button"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-id="{{ $t->id }}"
                                    data-name="{{ $t->unit->nama_unit }}">
                                Hapus
                            </button>
                        </td>
                    @endif
                </tr>
            @endforeach

            {{-- Total per unit --}}
            <tr>
                <td colspan="3">
                    <strong>Total {{ $t->unit->nama_unit }}</strong>
                </td>
                <td colspan="2">
                    <strong>Rp {{ number_format($t->total_amount,0,',','.') }}</strong>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    Belum ada data pengeluaran transport
                </td>
            </tr>
        @endforelse

        {{-- Total Keseluruhan --}}
        <tr>
            <td colspan="4"><strong>Total Keseluruhan</strong></td>
            <td colspan="2">
                <strong>Rp {{ number_format($total_all,0,',','.') }}</strong>
            </td>
        </tr>
    </tbody>
</table>


</div>

<!-- SUCCESS MODAL -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body text-center py-4">

                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size:60px;"></i>
                </div>

                <h5 class="fw-bold mb-2">Berhasil</h5>
                <div id="successText" class="text-muted"></div>

                <div class="mt-4">
                    <button class="btn btn-primary px-4" data-bs-dismiss="modal">
                        OK
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>

{{-- ================= MODAL HAPUS ================= --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body text-center py-4">

                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger"
                       style="font-size:60px;"></i>
                </div>

                <h5 class="fw-bold mb-2">Hapus Data Pengeluaran Transport</h5>

                <p class="text-muted mb-4">
                    Data ini akan dihapus permanen dan tidak bisa dikembalikan.
                </p>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="d-flex justify-content-center gap-2">
                        <button style="margin-right: 7px" type="button"
                                class="btn btn-secondary px-4"
                                data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit"
                                class="btn btn-danger px-4">
                            Ya, Hapus
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<!-- LOADING MODAL -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Memuat data...</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){

    const filterForm = document.getElementById("filterForm");
    if(!filterForm) return;

    const modal = new bootstrap.Modal(document.getElementById("loadingModal"));

    filterForm.addEventListener("submit", function(e){
        e.preventDefault();

        modal.show();

        setTimeout(() => {
            filterForm.submit();
        }, 150);
    });

});
</script>

{{-- ================= SCRIPT MODAL HAPUS ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm  = document.getElementById('deleteForm');
    const unitName    = document.getElementById('deleteUnitName');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id     = button.getAttribute('data-id');
        const name   = button.getAttribute('data-name');

        // assign nama unit jika ingin tampil di modal
        if(unitName) unitName.textContent = name;

        // assign action form DELETE ke route resource
        deleteForm.action = `/pengeluaran_transport/${id}`;
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    const successInput = document.getElementById("success-message");

    if(successInput){
        const modal = new bootstrap.Modal(document.getElementById("successModal"));
        document.getElementById("successText").innerText = successInput.value;

        // kasih delay biar page render dulu
        setTimeout(() => {
            modal.show();
        }, 250);
    }
});

document.addEventListener("DOMContentLoaded", function(){
    const laporanBtn = document.getElementById("btnLaporan");
    if(!laporanBtn) return;

    const modal = new bootstrap.Modal(document.getElementById("loadingModal"));

    laporanBtn.addEventListener("click", function(e){
        e.preventDefault();
        modal.show();

        // kasih delay sedikit biar modal kelihatan
        setTimeout(() => {
            window.location.href = laporanBtn.href;
        }, 150);
    });
});

</script>
@endsection

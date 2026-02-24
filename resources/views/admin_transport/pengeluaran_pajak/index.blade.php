@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Pengeluaran Pajak Mobil</h4>

    
    {{-- ALERT --}}
   @if (session('success'))
    <input type="hidden" id="success-message" value="{{ session('success') }}">
@endif

    <form method="GET" class="mb-3" id="filterForm">
        <input type="month"
               name="bulan"
               value="{{ $bulan }}"
               class="form-control w-auto d-inline">

        {{-- SEARCH DESKRIPSI --}}
        <input style="margin-right: 10px" type="text"
               name="search"
               value="{{ $search ?? '' }}"
               class="form-control w-auto d-inline"
               placeholder="Cari deskripsi">

        <button type="submit" class="btn btn-primary">
            Filter
        </button>

        <a href="{{ route('pengeluaran_pajak.create') }}"
           class="btn btn-success">
            Tambah Pajak
        </a>

        <a href="{{ route('pengeluaran_pajak.laporan', ['bulan'=>$bulan]) }}"
           class="btn btn-info" id="btnLaporan">
            Laporan Bulanan
        </a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Plat Nomor</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pajak as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->unit->nama_unit }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>
                <td>
                    @if($item->gambar)
                        <img src="{{ asset('storage/'.$item->gambar) }}"
                             alt="Gambar"
                             width="80"
                             class="img-thumbnail">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('pengeluaran_pajak.show', $item->id) }}"
   class="btn btn-info btn-sm mb-1">
    Detail
</a>
                    <a href="{{ route('pengeluaran_pajak.edit', $item->id) }}"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form action="{{ route('pengeluaran_pajak.destroy', $item->id) }}"
                          method="POST"
                          style="display:inline;">
                        @csrf
                        @method('DELETE')

                       <button type="button"
            class="btn btn-danger btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#deleteModal" data-id="{{ $item->id }}" data-name="{{ $item->deskripsi }}">
        Hapus
    </button>
                    </form>
                </td>
            </tr>
            @endforeach

            <tr>
                <td colspan="4"><strong>Total</strong></td>
                <td colspan="3">
                    <strong>Rp {{ number_format($total,0,',','.') }}</strong>
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
<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body text-center py-4">

                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger"
                       style="font-size:60px;"></i>
                </div>

                <h5 class="fw-bold mb-2">Hapus Data Pengeluaran?</h5>

                <p class="text-muted mb-4">
                    Pengeluaran <strong id="deleteUnitName"></strong> akan dihapus permanen
                    dan tidak bisa dikembalikan.
                </p>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button"
                                class="btn btn-secondary px-4" style="margin-right: 10px"
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

<script>
document.addEventListener("DOMContentLoaded", function () {

    const deleteModal = document.getElementById('deleteModal');
    const deleteForm  = document.getElementById('deleteForm');
    const unitName    = document.getElementById('deleteUnitName');

    deleteModal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;
        const id     = button.getAttribute('data-id');
        const name   = button.getAttribute('data-name');

        unitName.textContent = name;
        deleteForm.action = `/pengeluaran_pajak/${id}`;
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

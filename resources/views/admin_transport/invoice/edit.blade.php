@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Invoice</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('invoice.update', $invoice->id) }}"
          method="POST"
          enctype="multipart/form-data"
          onsubmit="return submitWithLoading()"
>
        @csrf
        @method('PUT')

        {{-- MITRA --}}
        <input type="hidden" name="mitra_id" value="{{ $invoice->mitra->id }}">
        <div class="mb-3">
            <label>Mitra</label>
            <input type="text"
                   class="form-control"
                   value="{{ $invoice->mitra->nama_mitra }}"
                   readonly>
        </div>

        <hr>

        <h5>Item Invoice</h5>

        <div class="table-responsive">
            <table class="table table-bordered" id="items">
                <thead class="table-light">
                <tr>
                    <th style="min-width:180px;">No Invoice</th>
                    <th style="min-width:140px;">Tanggal Invoice</th>
                    <th style="min-width:220px;">Item</th>
                    <th style="min-width:140px;">Tanggal TF</th>
                    <th style="min-width:140px;">Cicilan</th>
                    <th style="min-width:140px;">Tagihan</th>
                    <th style="min-width:140px;">Bukti Transfer</th>
                    <th style="min-width:140px;">Bukti Perjalanan</th>
                    <th style="width:60px;"></th>
                </tr>
                </thead>

                <tbody>
                @foreach($invoice->items as $i => $item)
                <tr>
                    <td>
                        <input type="hidden"
                               name="items[{{ $i }}][id]"
                               value="{{ $item->id }}">

                        <input type="text"
                               name="items[{{ $i }}][no_invoices]"
                               class="form-control"
                               value="{{ $item->no_invoices }}">
                    </td>

                    <td>
                        <input type="date"
                               name="items[{{ $i }}][tanggal_invoices]"
                               class="form-control"
                               value="{{ $item->tanggal_invoices
                                   ? \Carbon\Carbon::parse($item->tanggal_invoices)->format('Y-m-d')
                                   : '' }}">
                    </td>

                    <td>
                        <input name="items[{{ $i }}][item]"
                               class="form-control"
                               value="{{ $item->item }}"
                               required>
                    </td>

                    <td>
                        <input type="date"
                               name="items[{{ $i }}][tanggal_tf]"
                               class="form-control"
                               value="{{ $item->tanggal_tf
                                   ? \Carbon\Carbon::parse($item->tanggal_tf)->format('Y-m-d')
                                   : '' }}">
                    </td>

                    <td>
                        <input type="text"
                               class="form-control rupiah"
                               data-hidden="items[{{ $i }}][cicilan]"
                               value="Rp {{ number_format($item->cicilan,0,',','.') }}">
                        <input type="hidden"
                               name="items[{{ $i }}][cicilan]"
                               value="{{ $item->cicilan }}">
                    </td>

                    <td>
                        <input type="text"
                               class="form-control rupiah"
                               data-hidden="items[{{ $i }}][tagihan]"
                               value="Rp {{ number_format($item->tagihan,0,',','.') }}">
                        <input type="hidden"
                               name="items[{{ $i }}][tagihan]"
                               value="{{ $item->tagihan }}">
                    </td>

                    <td>
                        @if($item->gambar_transfer)
                            <img src="{{ asset('storage/'.$item->gambar_transfer) }}"
                                 width="60"
                                 class="d-block mb-1">
                        @endif
                        <input type="file"
                               name="items[{{ $i }}][gambar_transfer]"
                               class="form-control"
                               accept="image/*">
                    </td>

                    <td>
                        @if($item->gambar_trip)
                            <img src="{{ asset('storage/'.$item->gambar_trip) }}"
                                 width="60"
                                 class="d-block mb-1">
                        @endif
                        <input type="file"
                               name="items[{{ $i }}][gambar_trip]"
                               class="form-control"
                               accept="image/*">
                    </td>

                    <td class="text-center">
                        <button type="button"
                                class="btn btn-danger btn-sm"
                                onclick="this.closest('tr').remove()">
                            hapus
                        </button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <button type="button"
                class="btn btn-sm btn-secondary mt-3 mb-3"
                onclick="addItem()">+ Item</button>

        <hr>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('invoice.show', $invoice->mitra->id) }}"
           class="btn btn-secondary">Batal</a>
    </form>
</div>
<!-- MODAL LOADING UPDATE -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Memperbarui data...</div>
            </div>
        </div>
    </div>
</div>


<script>
let i = {{ $invoice->items->count() }};

/* ===== UTIL ===== */
function bulanRomawi(b){
    return ['','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][b];
}
function pad3(n){
    return String(n).replace(/\D/g,'').padStart(3,'0');
}

/* ===== RUPIAH ===== */
function formatRupiah(a){
    let s = a.replace(/\D/g,'');
    return s ? 'Rp ' + s.replace(/\B(?=(\d{3})+(?!\d))/g,'.') : '';
}
function bindRupiah(el){
    el.addEventListener('input',function(){
        let raw = this.value.replace(/\D/g,'');
        this.value = formatRupiah(raw);
        this.parentElement.querySelector(
            `input[name="${this.dataset.hidden}"]`
        ).value = raw || 0;
    });
}
document.querySelectorAll('.rupiah').forEach(bindRupiah);

/* ===== ADD ITEM ===== */
function addItem(){
    let row = `
    <tr>
        <td><input name="items[${i}][no_invoices]" class="form-control"></td>
        <td><input type="date" name="items[${i}][tanggal_invoices]" class="form-control"></td>
        <td><input name="items[${i}][item]" class="form-control" required></td>
        <td><input type="date" name="items[${i}][tanggal_tf]" class="form-control"></td>
        <td>
            <input class="form-control rupiah" data-hidden="items[${i}][cicilan]">
            <input type="hidden" name="items[${i}][cicilan]" value="0">
        </td>
        <td>
            <input class="form-control rupiah" data-hidden="items[${i}][tagihan]">
            <input type="hidden" name="items[${i}][tagihan]" value="0">
        </td>
        <td>
            <input type="file"
                   name="items[${i}][gambar_transfer]"
                   class="form-control"
                   accept="image/*">
        </td>
        <td>
            <input type="file"
                   name="items[${i}][gambar_trip]"
                   class="form-control"
                   accept="image/*">
        </td>
        <td class="text-center">
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="this.closest('tr').remove()">hapus</button>
        </td>
    </tr>`;

    document.querySelector('#items tbody')
        .insertAdjacentHTML('beforeend', row);

    document.querySelectorAll('.rupiah').forEach(bindRupiah);
    i++;
}

/* ===== SUBMIT ===== */
function cekItem(){
    for(let row of document.querySelectorAll('#items tbody tr')){
        let no  = row.querySelector('input[name*="[no_invoices]"]');
        let tgl = row.querySelector('input[name*="[tanggal_invoices]"]');

        if(!no.value || !tgl.value){
            alert('No Invoice dan Tanggal Invoice wajib diisi');
            return false;
        }

        let d = new Date(tgl.value);
        let raw = no.value.split('/')[0];

        no.value =
            `${pad3(raw)}/BJM/${bulanRomawi(d.getMonth()+1)}/${d.getFullYear()}`;
    }
    return true;
}

function submitWithLoading(){
    if(!cekItem()) return false;

    let modal = new bootstrap.Modal(
        document.getElementById('loadingModal')
    );
    modal.show();

    return true; // lanjut submit
}
</script>
@endsection

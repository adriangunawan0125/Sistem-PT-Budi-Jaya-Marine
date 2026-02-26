@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Edit Invoice</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('invoice.update', $invoice->id) }}"
          method="POST"
          enctype="multipart/form-data"
          onsubmit="return submitWithLoading()">
        @csrf
        @method('PUT')

        {{-- MITRA --}}
        <input type="hidden" name="mitra_id" value="{{ $invoice->mitra->id }}">

        <div class="mb-3">
            <label class="form-label small mb-1">Mitra</label>
            <input type="text"
                   class="form-control form-control-sm"
                   value="{{ $invoice->mitra->nama_mitra }}"
                   readonly>
        </div>

        <hr>

        <h5 class="mb-3">Item Invoice</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle invoice-table" id="items">
                <thead class="table-light text-center">
                <tr>
                    <th>No Invoice</th>
                    <th>Tgl Invoice</th>
                    <th>Item</th>
                    <th>Tgl TF</th>
                    <th>Cicilan</th>
                    <th>Tagihan</th>
                    <th>Bukti Trip</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @foreach($invoice->items as $i => $item)
                <tr>

                    <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">

                    <td>
                        <input name="items[{{ $i }}][no_invoices]"
                               class="form-control form-control-sm"
                               value="{{ $item->no_invoices }}">
                    </td>

                    <td>
                        <input type="date"
                               name="items[{{ $i }}][tanggal_invoices]"
                               class="form-control form-control-sm"
                               value="{{ $item->tanggal_invoices }}">
                    </td>

                    <td>
                        <input name="items[{{ $i }}][item]"
                               class="form-control form-control-sm"
                               value="{{ $item->item }}"
                               required>
                    </td>

                    <td>
                        <input type="date"
                               name="items[{{ $i }}][tanggal_tf]"
                               class="form-control form-control-sm"
                               value="{{ $item->tanggal_tf }}">
                    </td>

                    <td>
                        <input class="form-control form-control-sm rupiah"
                               data-hidden="items[{{ $i }}][cicilan]"
                               value="Rp {{ number_format($item->cicilan,0,',','.') }}">
                        <input type="hidden"
                               name="items[{{ $i }}][cicilan]"
                               value="{{ $item->cicilan }}">
                    </td>

                    <td>
                        <input class="form-control form-control-sm rupiah"
                               data-hidden="items[{{ $i }}][tagihan]"
                               value="Rp {{ number_format($item->tagihan,0,',','.') }}">
                        <input type="hidden"
                               name="items[{{ $i }}][tagihan]"
                               value="{{ $item->tagihan }}">
                    </td>

                    {{-- ================= TRIP ================= --}}
                    <td>
                        <div class="upload-group trip-group">

                            <div class="upload-buttons">
                                <label class="upload-btn">
                                    <i class="bi bi-image"></i>
                                    <input type="file"
                                           name="items[{{ $i }}][gambar_trip]"
                                           class="image-input trip-input"
                                           hidden>
                                </label>

                                <button type="button"
                                        class="btn btn-xs btn-outline-secondary"
                                        onclick="addTrip(this,{{ $i }})">
                                    +
                                </button>
                            </div>

                            <div class="preview-area">

                                @foreach(['gambar_trip','gambar_trip1'] as $field)
                                    @if($item->$field)
                                        <div class="preview-box">
                                            <img src="{{ asset('storage/'.$item->$field) }}"
                                                 class="preview-img">
                                            <span class="remove-img"
                                                  onclick="removeOldImage(this)">
                                                ×
                                            </span>
                                            <input type="hidden"
                                                   name="items[{{ $i }}][hapus_{{ $field }}]"
                                                   value="0">
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </td>

                    <td class="text-center">
                        <button type="button"
                                class="btn btn-danger btn-sm"
                                onclick="this.closest('tr').remove()">
                            Hapus
                        </button>
                    </td>

                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <button type="button"
                class="btn btn-sm btn-secondary mt-3 mb-3"
                onclick="addItem()">
            + Item
        </button>

        <hr>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('invoice.show', $invoice->mitra->id) }}"
           class="btn btn-secondary">Batal</a>
    </form>
</div>

<style>
.invoice-table th,.invoice-table td{
    font-size:13px;
    padding:8px;
    vertical-align:middle;
}
.upload-group{display:flex;flex-direction:column;gap:6px;}
.upload-buttons{display:flex;gap:6px;align-items:center;}
.upload-btn{
    width:34px;height:34px;display:flex;
    align-items:center;justify-content:center;
    background:#f1f3f5;border:1px solid #ddd;
    border-radius:6px;cursor:pointer;
}
.preview-area{display:flex;gap:6px;flex-wrap:wrap;}
.preview-box{position:relative;}
.preview-img{
    width:42px;height:42px;object-fit:cover;
    border-radius:6px;border:1px solid #ddd;
}
.remove-img{
    position:absolute;top:-6px;right:-6px;
    background:#fff;border-radius:50%;
    font-size:12px;cursor:pointer;color:red;
}
</style>

<script>
function removeOldImage(btn){
    const box = btn.closest(".preview-box");
    const hidden = box.querySelector("input[type=hidden]");
    hidden.value = 1;
    box.style.opacity = 0.3;
}
</script>

@endsection

<script>
let i = {{ $invoice->items->count() }};

/* ================= RUPIAH FORMAT ================= */
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


/* ================= PREVIEW GAMBAR BARU ================= */
document.addEventListener("change", function(e){

    if(e.target.classList.contains("image-input")){

        const input = e.target;
        if(!input.files.length) return;

        const group = input.closest(".upload-group");
        const preview = group.querySelector(".preview-area");

        const reader = new FileReader();

        reader.onload = function(ev){

            const box = document.createElement("div");
            box.className = "preview-box";

            box.innerHTML = `
                <img src="${ev.target.result}" class="preview-img">
                <span class="remove-img">×</span>
            `;

            preview.appendChild(box);

            box.querySelector(".remove-img").onclick = function(){
                input.value = "";
                box.remove();
            }
        }

        reader.readAsDataURL(input.files[0]);
    }

});


/* ================= HAPUS GAMBAR LAMA ================= */
function removeOldImage(btn){

    const box = btn.closest(".preview-box");
    const hidden = box.querySelector("input[type=hidden]");

    if(hidden){
        hidden.value = 1; // kirim ke controller untuk delete
    }

    box.style.opacity = 0.3;
}

/* ================= ADD TRIP ================= */
function addTrip(btn,index){

    const group = btn.closest(".trip-group");
    const btnArea = group.querySelector(".upload-buttons");
    const count = btnArea.querySelectorAll(".trip-input").length;

    if(count >= 2){
        alert("Maksimal 2 bukti trip");
        return;
    }

    btnArea.insertAdjacentHTML("afterbegin",`
        <label class="upload-btn">
            <i class="bi bi-image"></i>
            <input type="file"
                   name="items[${index}][gambar_trip1]"
                   class="image-input trip-input"
                   hidden>
        </label>
    `);
}


/* ================= ADD ITEM BARU ================= */
function addItem(){

    let row = `
    <tr>

        <td>
            <input name="items[${i}][no_invoices]"
                   class="form-control form-control-sm">
        </td>

        <td>
            <input type="date"
                   name="items[${i}][tanggal_invoices]"
                   class="form-control form-control-sm">
        </td>

        <td>
            <input name="items[${i}][item]"
                   class="form-control form-control-sm"
                   required>
        </td>

        <td>
            <input type="date"
                   name="items[${i}][tanggal_tf]"
                   class="form-control form-control-sm">
        </td>

        <td>
            <input class="form-control form-control-sm rupiah"
                   data-hidden="items[${i}][cicilan]">
            <input type="hidden"
                   name="items[${i}][cicilan]"
                   value="0">
        </td>

        <td>
            <input class="form-control form-control-sm rupiah"
                   data-hidden="items[${i}][tagihan]">
            <input type="hidden"
                   name="items[${i}][tagihan]"
                   value="0">
        </td>
            {{-- ================= TRIP ================= --}}

        <td>
            <div class="upload-group trip-group">
                <div class="upload-buttons">
                    <label class="upload-btn">
                        <i class="bi bi-image"></i>
                        <input type="file"
                               name="items[${i}][gambar_trip]"
                               class="image-input trip-input"
                               hidden>
                    </label>

                    <button type="button"
                            class="btn btn-xs btn-outline-secondary"
                            onclick="addTrip(this,${i})">
                        +
                    </button>
                </div>
                <div class="preview-area"></div>
            </div>
        </td>

        <td class="text-center">
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="this.closest('tr').remove()">
                Hapus
            </button>
        </td>

    </tr>`;

    document.querySelector('#items tbody')
        .insertAdjacentHTML('beforeend', row);

    document.querySelectorAll('.rupiah').forEach(bindRupiah);

    i++;
}


/* ================= LOADING ================= */
function submitWithLoading(){

    let modal = new bootstrap.Modal(
        document.getElementById('loadingModal')
    );
    modal.show();

    return true;
}
</script>
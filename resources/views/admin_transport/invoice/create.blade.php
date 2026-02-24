@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Buat Invoice</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('invoice.store') }}"
          method="POST"
          enctype="multipart/form-data"
          onsubmit="return submitWithLoading()">
        @csrf

        {{-- MITRA --}}
        @if(isset($mitra))
            <input type="hidden" name="mitra_id" value="{{ $mitra->id }}">
            <div class="mb-3">
                <label class="form-label small mb-1">Mitra</label>
                <input type="text"
                       class="form-control form-control-sm"
                       value="{{ $mitra->nama_mitra }}"
                       readonly>
            </div>
        @else
            <div class="mb-3">
                <label class="form-label small mb-1">Mitra</label>
                <select name="mitra_id" class="form-control form-control-sm" required>
                    <option value="">-- Pilih Mitra --</option>
                    @foreach($mitras as $m)
                        <option value="{{ $m->id }}">{{ $m->nama_mitra }}</option>
                    @endforeach
                </select>
            </div>
        @endif

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
                    <th>Bukti Transfer</th>
                    <th>Bukti Trip</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td><input name="items[0][no_invoices]" class="form-control form-control-sm"></td>
                    <td><input type="date" name="items[0][tanggal_invoices]" class="form-control form-control-sm"></td>
                    <td><input name="items[0][item]" class="form-control form-control-sm" required></td>
                    <td><input type="date" name="items[0][tanggal_tf]" class="form-control form-control-sm"></td>

                    <td>
                        <input class="form-control form-control-sm rupiah"
                               data-hidden="items[0][cicilan]"
                               placeholder="Rp 0">
                        <input type="hidden" name="items[0][cicilan]" value="0">
                    </td>

                    <td>
                        <input class="form-control form-control-sm rupiah"
                               data-hidden="items[0][tagihan]"
                               placeholder="Rp 0">
                        <input type="hidden" name="items[0][tagihan]" value="0">
                    </td>

                    {{-- TRANSFER --}}
                    <td>
                        <div class="upload-group transfer-group">
                            <div class="upload-buttons">
                                <label class="upload-btn">
                                    <i class="bi bi-upload"></i>
                                    <input type="file"
                                           name="items[0][gambar_transfer]"
                                           class="image-input transfer-input"
                                           hidden>
                                </label>

                                <button type="button"
                                        class="btn btn-xs btn-outline-secondary"
                                        onclick="addTransfer(this,0)">
                                    +
                                </button>
                            </div>
                            <div class="preview-area"></div>
                        </div>
                    </td>

                    {{-- TRIP --}}
                    <td>
                        <div class="upload-group trip-group">
                            <div class="upload-buttons">
                                <label class="upload-btn">
                                    <i class="bi bi-image"></i>
                                    <input type="file"
                                           name="items[0][gambar_trip]"
                                           class="image-input trip-input"
                                           hidden>
                                </label>

                                <button type="button"
                                        class="btn btn-xs btn-outline-secondary"
                                        onclick="addTrip(this,0)">
                                    +
                                </button>
                            </div>
                            <div class="preview-area"></div>
                        </div>
                    </td>

                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>

        <button type="button"
                class="btn btn-sm btn-secondary mt-3 mb-3"
                onclick="addItem()">
            + Item
        </button>

        <hr>

        <button class="btn btn-primary">Simpan</button>

        @if(isset($mitra))
            <a href="{{ route('invoice.show', $mitra->id) }}" class="btn btn-secondary">Batal</a>
        @else
            <a href="{{ route('invoice.index') }}" class="btn btn-secondary">Batal</a>
        @endif
    </form>
</div>

{{-- LOADING --}}
<div class="modal fade" id="loadingModal" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Menyimpan data...</div>
            </div>
        </div>
    </div>
</div>

<style>
.invoice-table th,
.invoice-table td{
    font-size:13px;
    padding:8px;
    vertical-align:middle;
}

.upload-group{
    display:flex;
    flex-direction:column;
    gap:6px;
}

.upload-buttons{
    display:flex;
    gap:6px;
    align-items:center;
}

.upload-btn{
    width:34px;
    height:34px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#f1f3f5;
    border:1px solid #ddd;
    border-radius:6px;
    cursor:pointer;
}

.preview-area{
    display:flex;
    gap:6px;
    flex-wrap:wrap;
}

.preview-box{
    position:relative;
}

.preview-img{
    width:42px;
    height:42px;
    object-fit:cover;
    border-radius:6px;
    border:1px solid #ddd;
}

.remove-img{
    position:absolute;
    top:-6px;
    right:-6px;
    background:#fff;
    border-radius:50%;
    font-size:12px;
    cursor:pointer;
    color:red;
}
</style>

<script>
let i = 1;

/* FORMAT RUPIAH */
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

/* PREVIEW GLOBAL */
document.addEventListener("change", function(e){

    if(e.target.classList.contains("image-input")){

        const input = e.target;
        const group = input.closest(".upload-group");
        const preview = group.querySelector(".preview-area");

        if(!input.files.length) return;

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

/* ADD TRANSFER */
function addTransfer(btn,index){

    const group = btn.closest(".transfer-group");
    const btnArea = group.querySelector(".upload-buttons");
    const count = btnArea.querySelectorAll(".transfer-input").length;

    if(count >= 3){
        alert("Maksimal 3 bukti transfer");
        return;
    }

    btnArea.insertAdjacentHTML("afterbegin",`
        <label class="upload-btn">
            <i class="bi bi-upload"></i>
            <input type="file"
                   name="items[${index}][gambar_transfer${count}]"
                   class="image-input transfer-input"
                   hidden>
        </label>
    `);
}

/* ADD TRIP */
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
                   name="items[${index}][gambar_trip${count}]"
                   class="image-input trip-input"
                   hidden>
        </label>
    `);
}

/* ADD ITEM */
function addItem(){

    let row = `
    <tr>
        <td><input name="items[${i}][no_invoices]" class="form-control form-control-sm"></td>
        <td><input type="date" name="items[${i}][tanggal_invoices]" class="form-control form-control-sm"></td>
        <td><input name="items[${i}][item]" class="form-control form-control-sm" required></td>
        <td><input type="date" name="items[${i}][tanggal_tf]" class="form-control form-control-sm"></td>

        <td>
            <input class="form-control form-control-sm rupiah"
                   data-hidden="items[${i}][cicilan]"
                   placeholder="Rp 0">
            <input type="hidden" name="items[${i}][cicilan]" value="0">
        </td>

        <td>
            <input class="form-control form-control-sm rupiah"
                   data-hidden="items[${i}][tagihan]"
                   placeholder="Rp 0">
            <input type="hidden" name="items[${i}][tagihan]" value="0">
        </td>

        <td>
            <div class="upload-group transfer-group">
                <div class="upload-buttons">
                    <label class="upload-btn">
                        <i class="bi bi-upload"></i>
                        <input type="file"
                               name="items[${i}][gambar_transfer]"
                               class="image-input transfer-input"
                               hidden>
                    </label>

                    <button type="button"
                            class="btn btn-xs btn-outline-secondary"
                            onclick="addTransfer(this,${i})">
                        +
                    </button>
                </div>
                <div class="preview-area"></div>
            </div>
        </td>

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
function submitWithLoading(){
    let modal = new bootstrap.Modal(
        document.getElementById('loadingModal')
    );
    modal.show();
    return true;
}
</script>
@endsection
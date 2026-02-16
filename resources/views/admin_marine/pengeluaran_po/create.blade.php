@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Tambah Pengeluaran PO</h4>

        <a href="{{ route('po-masuk.show',$poMasuk->id) }}"
           class="btn btn-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('pengeluaran-po.store') }}" method="POST">
                @csrf

                <input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

                <div class="mb-3">
                    <label class="form-label">PO Klien</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $poMasuk->no_po_klien }}"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Pengeluaran</label>
                    <input type="text"
                           name="item"
                           class="form-control"
                           required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Qty</label>
                        <input type="number"
                               step="0.01"
                               name="qty"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number"
                               step="0.01"
                               name="price"
                               class="form-control"
                               required>
                    </div>
                </div>

                <div class="text-end">
                    <button class="btn btn-primary">
                        Simpan Pengeluaran
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

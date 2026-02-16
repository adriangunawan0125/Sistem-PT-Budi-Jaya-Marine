@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Edit Pengeluaran</h4>

        <a href="{{ route('po-masuk.show',$pengeluaranPo->po_masuk_id) }}"
           class="btn btn-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('pengeluaran-po.update',$pengeluaranPo->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Pengeluaran</label>
                    <input type="text"
                           name="item"
                           value="{{ $pengeluaranPo->item }}"
                           class="form-control"
                           required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Qty</label>
                        <input type="number"
                               step="0.01"
                               name="qty"
                               value="{{ $pengeluaranPo->qty }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number"
                               step="0.01"
                               name="price"
                               value="{{ $pengeluaranPo->price }}"
                               class="form-control"
                               required>
                    </div>
                </div>

                <div class="text-end">
                    <button class="btn btn-warning">
                        Update Pengeluaran
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

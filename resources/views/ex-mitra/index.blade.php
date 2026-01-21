@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Ex Mitra</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('ex-mitra.create') }}" class="btn btn-primary mb-3">
        Tambah Ex Mitra
    </a>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="Cari nama ex mitra..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Cari</button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('ex-mitra.index') }}"
               class="btn btn-secondary w-100">
               Reset
            </a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th>Jaminan</th>
                    <th>No Handphone</th>
                    <th>Keterangan</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($exMitra as $no => $item)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jaminan ?? '-' }}</td>
                    <td>{{ $item->no_handphone }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('ex-mitra.edit', $item->id) }}"
                           class="btn btn-warning btn-sm">
                           Edit
                        </a>

                        <form action="{{ route('ex-mitra.destroy', $item->id) }}"
                              method="POST"
                              style="display:inline-block; margin-left:6px">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus ex mitra?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Data ex mitra tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Data Jaminan Mitra</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('jaminan_mitra.create') }}" class="btn btn-success mb-3">
        + Tambah Jaminan
    </a>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th style="min-width:60px">No</th>
                <th style="min-width:200px">Nama Mitra</th>
                <th style="min-width:150px">No HP</th>
                <th style="min-width:180px">Jaminan</th>
                <th style="min-width:220px">Gambar</th>
                <th style="min-width:130px" class="text-nowrap">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->mitra->nama_mitra }}</td>
                <td>{{ $item->mitra->no_hp }}</td>
                <td>{{ $item->jaminan }}</td>
                <td>
                    @foreach(['gambar_1','gambar_2','gambar_3'] as $g)
                        @if($item->$g)
                            <a href="{{ asset('storage/'.$item->$g) }}" target="_blank">
                                <img src="{{ asset('storage/'.$item->$g) }}"
                                     width="50"
                                     class="me-1 mb-1 rounded border">
                            </a>
                        @endif
                    @endforeach
                </td>
                <td class="text-nowrap">
                    <a href="{{ route('jaminan_mitra.edit', $item->id) }}"
                       class="btn btn-warning btn-sm me-1">
                        Edit
                    </a>

                    <form action="{{ route('jaminan_mitra.destroy', $item->id) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin hapus data?')"
                                class="btn btn-danger btn-sm">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Data belum ada</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

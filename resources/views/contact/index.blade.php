@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Pesan Hubungi Kami</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- SEARCH --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama pengirim..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Cari</button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('contact.index') }}"
               class="btn btn-secondary w-100">
                Reset
            </a>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No Telepon</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($messages as $no => $item)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->no_telepon ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('contact.show', $item->id) }}"
                           class="btn btn-info btn-sm">
                           Detail
                        </a>

                        <form action="{{ route('contact.destroy', $item->id) }}"
                              method="POST"
                              style="display:inline-block; margin-left:6px">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus pesan ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Data pesan tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

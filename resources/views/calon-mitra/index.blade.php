@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Calon Mitra</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th>No Handphone</th>
                    <th>Alamat</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($calonmitra as $no => $item)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->no_handphone }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td class="text-center">
                        <a href="{{ route('calonmitra.show', $item->id) }}" 
   class="btn btn-info btn-sm">
   Detail
</a>

                        <form action="{{ route('admin.calonmitra.destroy', $item->id) }}"
                              method="POST"
                              style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus data calon mitra?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Data calon mitra tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

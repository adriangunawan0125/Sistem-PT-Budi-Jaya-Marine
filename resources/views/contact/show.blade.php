@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Detail Pesan</h4>

    <div class="card">
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $message->nama }}</p>
            <p><strong>Email:</strong> {{ $message->email }}</p>
            <p><strong>No Telepon:</strong> {{ $message->no_telepon ?? '-' }}</p>
            <p><strong>Pesan:</strong></p>
            <div class="border p-3 rounded bg-light">
                {{ $message->pesan }}
            </div>
        </div>
    </div>

    <a href="{{ route('contact.index') }}" class="btn btn-secondary mt-3">
        Kembali
    </a>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Tambah Akun</h4>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body px-4 py-4">

            <form action="{{ route('akun.store') }}" method="POST">
                @csrf

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="form-label small mb-1">Nama</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-control form-control-sm @error('name') is-invalid @enderror"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="form-label small mb-1">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control form-control-sm @error('email') is-invalid @enderror"
                           placeholder="Masukkan email"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ROLE --}}
                <div class="mb-3">
                    <label class="form-label small mb-1">Role</label>
                    <select name="role"
                            class="form-control form-control-sm filter-control @error('role') is-invalid @enderror"
                            required>
                        <option value="">Pilih Role</option>
                        <option value="admin_marine"
                            {{ old('role') == 'admin_marine' ? 'selected' : '' }}>
                            Admin Marine
                        </option>
                        <option value="admin_transport"
                            {{ old('role') == 'admin_transport' ? 'selected' : '' }}>
                            Admin Transport
                        </option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="mb-4">
                    <label class="form-label small mb-1">Password</label>
                    <input type="password"
                           name="password"
                           class="form-control form-control-sm @error('password') is-invalid @enderror"
                           placeholder="Minimal 6 karakter"
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="d-flex justify-content gap-2">
                    <a href="{{ route('akun.index') }}"
                       class="btn btn-secondary btn-sm px-3" style="margin-right: 4px">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-primary btn-sm px-4">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<style>
.card{
    border-radius:8px;
}

.form-label.small{
    font-weight:500;
}

.form-control-sm{
    padding:7px 10px;
    font-size:13px;
}

.filter-control{
    border-radius:6px;
}
</style>

@endsection
@extends('layouts.app')

@section('content')
<div class="container">

{{-- SUCCESS --}}
@if (session('success'))
    <input type="hidden" id="success-message" value="{{ session('success') }}">
@endif

{{-- ERROR --}}
@if (session('error'))
    <input type="hidden" id="error-message" value="{{ session('error') }}">
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Kelola Akun</h4>
    

    
</div>
{{-- FILTER --}}
<div class="card mb-3 shadow-sm">
    <div class="card-body py-3">

        <form method="GET" action="{{ route('akun.index') }}">
            <div class="row align-items-end g-3">

                {{-- SEARCH --}}
                <div class="col-md-5">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control form-control-sm"
                           placeholder="Nama atau Email">
                </div>

                {{-- ROLE FILTER --}}
                <div class="col-md-3">
                    <label class="form-label small mb-1">Role</label>
                    <select name="role"
                            class="form-control form-control-sm filter-control">
                        <option value="">Semua Role</option>
                        <option value="owner"
                            {{ request('role') == 'owner' ? 'selected' : '' }}>
                            Owner
                        </option>
                        <option value="admin_marine"
                            {{ request('role') == 'admin_marine' ? 'selected' : '' }}>
                            Admin Marine
                        </option>
                        <option value="admin_transport"
                            {{ request('role') == 'admin_transport' ? 'selected' : '' }}>
                            Admin Transport
                        </option>
                    </select>
                </div>

                {{-- BUTTON --}}
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit"
                            class="btn btn-primary btn-sm px-3" style="margin-right: 4px">
                        Filter
                    </button>

                    <a href="{{ route('akun.index') }}"
                       class="btn btn-secondary btn-sm" style="margin-right: 24px">
                        Reset
                    </a>
                    <a href="{{ route('akun.create') }}"
       class="btn btn-primary btn-sm px-3">
        + Tambah Akun
    </a>
                </div>

            </div>
        </form>

    </div>
</div>

<div class="card shadow-sm">
<div class="card-body p-0">

<div class="table-responsive">
<table class="table table-bordered table-hover align-middle akun-table mb-0">

<thead class="table-light text-center">
<tr>
<th width="60">No</th>
<th>Nama</th>
<th>Email</th>
<th width="180">Role</th>
<th width="220">Action</th>
</tr>
</thead>

<tbody>

@forelse($users as $index => $user)
<tr>

<td class="text-center">
    {{ $loop->iteration }}
</td>

<td class="fw-semibold">
    {{ $user->name }}
</td>

<td>
    {{ $user->email }}
</td>

<td class="text-center">
    <span class="badge text-light px-3 py-2
        @if($user->role == 'owner') bg-dark
        @elseif($user->role == 'admin_marine') bg-primary
        @else bg-success
        @endif">
        {{ strtoupper(str_replace('_',' ', $user->role)) }}
    </span>
</td>

<td>
<div class="aksi-wrapper">

    {{-- EDIT --}}
    <a href="{{ route('akun.edit',$user->id) }}"
       class="btn btn-warning btn-sm">
        Edit
    </a>

    {{-- RESET --}}
    <button type="button"
            class="btn btn-info btn-sm btnReset"
            data-id="{{ $user->id }}"
            data-name="{{ $user->name }}">
        Reset
    </button>

    {{-- DELETE --}}
    @if($user->id != auth()->id())
    <button type="button"
            class="btn btn-danger btn-sm btnDelete"
            data-id="{{ $user->id }}"
            data-name="{{ $user->name }}">
        Delete
    </button>
    @endif

</div>
</td>

</tr>

@empty
<tr>
<td colspan="5" class="text-center text-muted py-4">
Belum ada data akun
</td>
</tr>
@endforelse

</tbody>
</table>
</div>

</div>
</div>

</div>

{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-danger"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Hapus Akun?</h5>

<p class="text-muted">
Akun <strong id="deleteName"></strong>
akan dihapus permanen.
</p>

<form id="deleteForm" method="POST">
@csrf
@method('DELETE')

<div class="d-flex justify-content-center gap-2">
<button type="button"
        class="btn btn-secondary"
        data-bs-dismiss="modal" style="margin-right: 4px" >
Batal
</button>

<button type="submit"
        class="btn btn-danger">
Hapus
</button>
</div>

</form>

</div>
</div>
</div>
</div>

{{-- RESET PASSWORD MODAL --}}
<div class="modal fade" id="resetModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body py-4">

<h5 class="fw-bold mb-3 text-center">
Reset Password
</h5>

<p class="text-center text-muted">
Akun: <strong id="resetName"></strong>
</p>

<form id="resetForm" method="POST">
@csrf
@method('PUT')

<div class="mb-3">
<label class="form-label small">Password Baru</label>
<input type="password"
       name="password"
       class="form-control"
       required>
</div>

<div class="d-flex justify-content-center gap-2">
<button type="button"
        class="btn btn-secondary"
        data-bs-dismiss="modal" style="margin-right: 4px">
Batal
</button>

<button type="submit"
        class="btn btn-primary">
Simpan
</button>
</div>

</form>

</div>
</div>
</div>
</div>

{{-- SUCCESS MODAL --}}
<div class="modal fade" id="successModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<i class="bi bi-check-circle-fill text-success"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Berhasil</h5>
<div id="successText" class="text-muted"></div>

<div class="mt-4">
<button class="btn btn-primary px-4"
        data-bs-dismiss="modal">
OK
</button>
</div>

</div>
</div>
</div>
</div>

<style>
.akun-table th,
.akun-table td{
    font-size:13px;
    padding:10px 12px;
    vertical-align:middle;
}

.table-hover tbody tr:hover{
    background-color:#f5f7fa;
}

.aksi-wrapper{
    display:flex;
    gap:6px;
    justify-content:center;
    align-items:center;
}

.btn-sm{
    font-size:12px;
    padding:5px 12px;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const resetModal  = new bootstrap.Modal(document.getElementById('resetModal'));

    const deleteForm = document.getElementById('deleteForm');
    const resetForm  = document.getElementById('resetForm');

    const deleteName = document.getElementById('deleteName');
    const resetName  = document.getElementById('resetName');

    document.querySelectorAll('.btnDelete').forEach(btn=>{
        btn.addEventListener('click', function(){
            deleteName.textContent = this.dataset.name;
            deleteForm.action = `/akun/${this.dataset.id}`;
            deleteModal.show();
        });
    });

    document.querySelectorAll('.btnReset').forEach(btn=>{
        btn.addEventListener('click', function(){
            resetName.textContent = this.dataset.name;
            resetForm.action = `/akun/${this.dataset.id}/reset-password`;
            resetModal.show();
        });
    });

    const successInput = document.getElementById("success-message");

    if(successInput){
        const successModal = new bootstrap.Modal(
            document.getElementById("successModal")
        );

        document.getElementById("successText").innerText =
            successInput.value;

        setTimeout(()=>{ successModal.show(); },200);
    }

});
</script>

@endsection
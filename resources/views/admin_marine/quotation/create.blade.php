@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Create Quotation</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('quotations.store') }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-body">

                {{-- MITRA --}}
                <div class="mb-3">
                    <label class="form-label">Mitra</label>
                    <select name="mitra_id" class="form-control" required>
                        <option value="">-- Select Mitra --</option>
                        @foreach($mitras as $m)
                            <option value="{{ $m->id }}" 
                                {{ old('mitra_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mitra }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- VESSEL --}}
                <div class="mb-3">
                    <label class="form-label">Vessel</label>
                    <select name="vessel_id" class="form-control" required>
                        <option value="">-- Select Vessel --</option>
                        @foreach($vessels as $v)
                            <option value="{{ $v->id }}" 
                                {{ old('vessel_id') == $v->id ? 'selected' : '' }}>
                                {{ $v->nama_vessel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ATTENTION --}}
                <div class="mb-3">
                    <label class="form-label">Attention</label>
                    <input type="text" 
                           name="attention" 
                           class="form-control"
                           value="{{ old('attention') }}">
                </div>

                {{-- DATE --}}
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" 
                           name="date" 
                           class="form-control"
                           value="{{ old('date', date('Y-m-d')) }}"
                           required>
                </div>

                {{-- PROJECT --}}
                <div class="mb-3">
                    <label class="form-label">Project</label>
                    <input type="text" 
                           name="project" 
                           class="form-control"
                           value="{{ old('project') }}">
                </div>

                {{-- PLACE --}}
                <div class="mb-3">
                    <label class="form-label">Place</label>
                    <input type="text" 
                           name="place" 
                           class="form-control"
                           value="{{ old('place') }}">
                </div>

            </div>

            <div class="card-footer text-end">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Save & Continue
                </button>
            </div>
        </div>

    </form>

</div>
@endsection

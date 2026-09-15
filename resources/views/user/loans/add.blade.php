@extends('layouts.app')

@section('title', 'Pinjam Alat')
@section('page-title', 'Form Peminjaman')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('user.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.tools.index') }}" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>Tools</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.loans.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>My Loans</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.fines.index') }}" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>My Fines</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Peminjaman Alat</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('user.loans.create') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="tool_id" class="form-label">Pilih Alat <span class="text-danger">*</span></label>
                            <select class="form-control @error('tool_id') is-invalid @enderror" id="tool_id" name="tool_id"
                                required>
                                <option value="">Pilih Alat</option>
                                @foreach($tools as $tool)
                                    <option value="{{ $tool->id }}" {{ request('tool_id') == $tool->id ? 'selected' : '' }} {{ old('tool_id') == $tool->id ? 'selected' : '' }}>
                                        {{ $tool->name }} (Stock: {{ $tool->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tool_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="loan_date" class="form-label">Tanggal Pinjam <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('loan_date') is-invalid @enderror"
                                        id="loan_date" name="loan_date" value="{{ old('loan_date', date('Y-m-d')) }}"
                                        required>
                                    @error('loan_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="return_date" class="form-label">Tanggal Kembali <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('return_date') is-invalid @enderror"
                                        id="return_date" name="return_date"
                                        value="{{ old('return_date', date('Y-m-d', strtotime('+3 days'))) }}" required>
                                    @error('return_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Ajukan Peminjaman
                            </button>
                            <a href="{{ route('user.loans.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
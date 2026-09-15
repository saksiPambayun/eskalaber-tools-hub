@extends('layouts.app')

@section('title', 'Update Department')
@section('page-title', 'Update Department')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Update Department</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.departments.doUpdate', $department->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Department <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $department->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update
                            </button>
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
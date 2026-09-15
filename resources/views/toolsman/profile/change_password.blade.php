@extends('layouts.app')

@section('title', 'Change Password')
@section('page-title', 'Change Password')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('toolsman.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.profile.change_password') }}" class="nav-link active">
            <i class="nav-icon fas fa-key"></i>
            <p>Change Password</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ubah Password</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('toolsman.profile.do_change_password') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                id="new_password" name="new_password" required minlength="8">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                        <a href="{{ route('toolsman.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
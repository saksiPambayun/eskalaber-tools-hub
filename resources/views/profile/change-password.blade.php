@extends('layouts.app')

@section('title', 'Change Password')
@section('page-title', 'Change Password')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('profile.index') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>My Profile</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('profile.change_password') }}" class="nav-link active">
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
                    <h5 class="card-title">Ubah Password</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('profile.update_password') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required
                                minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
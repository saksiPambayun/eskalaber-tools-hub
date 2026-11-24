@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('toolsman.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.payment-methods.index') }}" class="nav-link {{ request()->routeIs('toolsman.payment-methods.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-credit-card"></i>
            <p>Metode Pembayaran</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.fine-setting.index') }}" class="nav-link {{ request()->routeIs('toolsman.fine-setting.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-gavel"></i>
            <p>Pengaturan Denda</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.logo-setting.index') }}" class="nav-link {{ request()->routeIs('toolsman.logo-setting.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-image"></i>
            <p>Pengaturan Logo</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profile Saya</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>{{ auth()->user()->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ auth()->user()->email }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td><span class="badge bg-success">{{ auth()->user()->role }}</span></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ auth()->user()->department->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ auth()->user()->phone ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Toolsman Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar')
    <li class="nav-item">
        <a href="/" class="nav-link" target="_blank">
            <i class="nav-icon fas fa-globe"></i>
            <p>Landing Page</p>
        </a>
    <li class="nav-item">
        <a href="{{ route('toolsman.dashboard') }}" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.loans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>Loans</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.fines.index') }}" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Fines</p>
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
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalTools ?? 0 }}</h3>
                    <p>Total Tools</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalLoans ?? 0 }}</h3>
                    <p>Total Loans</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $pendingLoans ?? 0 }}</h3>
                    <p>Pending Loans</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($totalFines ?? 0, 0, ',', '.') }}</h3>
                    <p>Total Fines</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card card-outline card-danger">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-gavel mr-2"></i>Tarif Denda Global</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                    <p class="mb-2">Tarif saat ini: <strong>Rp {{ number_format($fineRate ?? 10000, 0, ',', '.') }}/hari</strong></p>
                    <form action="{{ route('toolsman.fine-setting.update') }}" method="POST" class="mb-2">
                        @csrf
                        <div class="input-group">
                            <input type="number" name="rate_per_day" min="0" max="1000000" step="1" class="form-control"
                                   value="{{ old('rate_per_day', $fineRate ?? 10000) }}" required>
                            <div class="input-group-append">
                                <button class="btn btn-danger">Simpan</button>
                            </div>
                        </div>
                        <small class="text-muted">Berlaku untuk semua alat & hanya denda baru. Batas Rp 0–1.000.000/hari.</small>
                    </form>
                    <a href="{{ route('toolsman.fine-setting.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-history"></i> Lihat Riwayat Tarif
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Welcome Toolsman</h3>
                </div>
                <div class="card-body">
                    <p>Selamat datang di dashboard Toolsman.</p>
                    <p>Anda login sebagai <strong>{{ auth()->user()->name }}</strong> dengan role <span
                            class="badge bg-success">{{ auth()->user()->role }}</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection

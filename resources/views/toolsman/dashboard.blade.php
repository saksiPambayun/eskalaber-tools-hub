@extends('layouts.app')

@section('title', 'Dasbor Petugas Alat')
@section('page-title', 'Dasbor')

@section('sidebar')
    <li class="nav-item">
        <a href="/" class="nav-link" target="_blank">
            <i class="nav-icon fas fa-globe"></i>
            <p>Halaman Utama</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.dashboard') }}" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dasbor</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.loans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>Pinjaman</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.fines.index') }}" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Pembayaran</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.payment-methods.index') }}"
            class="nav-link {{ request()->routeIs('toolsman.payment-methods.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-credit-card"></i>
            <p>Metode Pembayaran</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.fine-setting.index') }}"
            class="nav-link {{ request()->routeIs('toolsman.fine-setting.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-gavel"></i>
            <p>Pengaturan Pembayaran</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.logo-setting.index') }}"
            class="nav-link {{ request()->routeIs('toolsman.logo-setting.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-image"></i>
            <p>Pengaturan Logo</p>
        </a>
    </li>
@endsection

@section('content')
    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalTools ?? 0 }}</h3>
                    <p>Total Alat</p>
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
                    <p>Total Pinjaman</p>
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
                    <p>Peminjaman Menunggu</p>
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
                    <p>Total Pembayaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris Bawah: Tarif & Welcome -->
    <div class="row">
        <!-- Tarif Pembayaran -->
        <div class="col-md-5">
            <div class="card card-outline card-danger d-flex flex-column" style="height: 100%;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-gavel mr-2"></i>Tarif Pembayaran Global</h3>
                </div>
                <div class="card-body d-flex flex-column">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>@endif
                    @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>@endif

                    <p class="mb-3">Tarif saat ini: <br><strong class="text-danger" style="font-size: 1.5rem;">Rp
                            {{ number_format($fineRate ?? 10000, 0, ',', '.') }}/hari</strong></p>

                    <form action="{{ route('toolsman.fine-setting.update') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="input-group">
                            <input type="number" name="rate_per_day" min="0" max="1000000" step="1" class="form-control"
                                value="{{ old('rate_per_day', $fineRate ?? 10000) }}" required>
                            <div class="input-group-append">
                                <button class="btn btn-danger font-weight-bold">Simpan</button>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">Berlaku untuk semua alat & hanya pembayaran baru. Batas Rp
                            0–1.000.000/hari.</small>
                    </form>

                    <div class="mt-auto">
                        <a href="{{ route('toolsman.fine-setting.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="fas fa-history"></i> Lihat Riwayat Tarif
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="col-md-7">
            <div class="card d-flex flex-column" style="height: 100%;">
                <div class="card-header">
                    <h3 class="card-title">Selamat Datang, Petugas Alat</h3>
                </div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <div
                        style="width: 80px; height: 80px; background: var(--primary-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        <i class="fas fa-user-cog fa-3x" style="color: var(--primary);"></i>
                    </div>
                    <h4 class="mb-2 font-weight-bold" style="color: var(--dark);">Selamat Datang!</h4>
                    <p class="text-muted mb-1">Halo, <strong>{{ auth()->user()->name }}</strong></p>
                    <p class="mb-0">Anda login sebagai <span
                            class="badge bg-success px-3 py-2">{{ auth()->user()->role }}</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection

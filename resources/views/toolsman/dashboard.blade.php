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
        <div class="col-12">
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

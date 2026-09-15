@extends('layouts.app')

@section('title', 'User Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('user.dashboard') }}" class="nav-link active">
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
        <a href="{{ route('user.loans.index') }}" class="nav-link">
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
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $activeLoans ?? 0 }}</h3>
                    <p>Active Loans</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding"></i>
                </div>
                <a href="{{ route('user.loans.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalLoans ?? 0 }}</h3>
                    <p>Total Loans</p>
                </div>
                <div class="icon">
                    <i class="fas fa-list"></i>
                </div>
                <a href="{{ route('user.loans.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($totalFines ?? 0, 0, ',', '.') }}</h3>
                    <p>My Fines</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('user.fines.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Welcome User</h3>
                </div>
                <div class="card-body">
                    <p>Selamat datang di dashboard user.</p>
                    <p>Anda login sebagai <strong>{{ auth()->user()->name }}</strong> dengan role <span
                            class="badge bg-warning">{{ auth()->user()->role }}</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection
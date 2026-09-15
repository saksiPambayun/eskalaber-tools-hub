@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar')
    <li class="nav-item">
        <a href="/" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Landing Page</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-header">MANAGEMENT</li>
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Users</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.toolsmans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Toolsman</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.loans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>Loans</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.fines.index') }}" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Fines</p>
        </a>
    </li>
    <li class="nav-header">MASTER DATA</li>
    <li class="nav-item">
        <a href="{{ route('admin.departments.index') }}" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>Departments</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.places.index') }}" class="nav-link">
            <i class="nav-icon fas fa-map-marker-alt"></i>
            <p>Places</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.types.index') }}" class="nav-link">
            <i class="nav-icon fas fa-tag"></i>
            <p>Types</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.categories.index') }}" class="nav-link">
            <i class="nav-icon fas fa-folder"></i>
            <p>Categories</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.tools.index') }}" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>Tools</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.activity_logs.index') }}" class="nav-link">
            <i class="nav-icon fas fa-history"></i>
            <p>Activity Logs</p>
        </a>
    </li>
@endsection

@section('content')
    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalUsers ?? 0 }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalTools ?? 0 }}</h3>
                    <p>Total Tools</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
                <a href="{{ route('admin.tools.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
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
                <a href="{{ route('admin.loans.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
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
                <a href="{{ route('admin.fines.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Peminjaman</h3>
                </div>
                <div class="card-body">
                    <canvas id="loanStatusChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Peminjaman Per Bulan</h3>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Welcome to Admin Dashboard</h3>
                </div>
                <div class="card-body">
                    <p>Selamat datang di sistem manajemen alat Eskalaber Tools Hub.</p>
                    <p>Anda login sebagai <strong>{{ auth()->user()->name }}</strong> dengan role <span
                            class="badge bg-primary">{{ auth()->user()->role }}</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart Status Peminjaman
            const ctx1 = document.getElementById('loanStatusChart');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Approved', 'Borrowed', 'Returned', 'Rejected'],
                        datasets: [{
                            label: 'Jumlah',
                            data: [
                                        {{ $pendingLoans ?? 0 }},
                                        {{ $approvedLoans ?? 0 }},
                                        {{ $borrowedLoans ?? 0 }},
                                        {{ $returnedLoans ?? 0 }},
                                {{ $rejectedLoans ?? 0 }}
                            ],
                            backgroundColor: ['#ffc107', '#007bff', '#17a2b8', '#28a745', '#dc3545'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Chart Peminjaman Per Bulan
            const ctx2 = document.getElementById('monthlyChart');
            if (ctx2) {
                const monthlyData = @json($monthlyLoans);
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                const data = [];
                for (let i = 1; i <= 12; i++) {
                    data.push(monthlyData[i] || 0);
                }

                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Jumlah Peminjaman',
                            data: data,
                            backgroundColor: '#007bff',
                            borderColor: '#0056b3',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush

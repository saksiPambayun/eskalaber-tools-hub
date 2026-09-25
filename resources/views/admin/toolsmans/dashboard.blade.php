@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar')
    <li class="nav-item">
        <a href="/" class="nav-link" target="_blank">
            <i class="nav-icon fas fa-globe"></i>
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
    <li class="nav-item">
        <a href="{{ route('admin.activity_logs.index') }}" class="nav-link">
            <i class="nav-icon fas fa-history"></i>
            <p>Activity Logs</p>
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
@endsection

@section('content')
    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box"
                style="background: linear-gradient(135deg, #E85D04, #DC2F02); color: #fff; border-radius: 15px; overflow: hidden;">
                <div class="inner">
                    <h3 style="color: #fff; font-weight: 800;">{{ $totalUsers ?? 0 }}</h3>
                    <p style="color: rgba(255,255,255,0.9);">Total Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users" style="color: rgba(255,255,255,0.3); font-size: 70px;"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer"
                    style="background: rgba(0,0,0,0.1); color: #fff;">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box"
                style="background: linear-gradient(135deg, #F48C06, #E85D04); color: #fff; border-radius: 15px; overflow: hidden;">
                <div class="inner">
                    <h3 style="color: #fff; font-weight: 800;">{{ $totalTools ?? 0 }}</h3>
                    <p style="color: rgba(255,255,255,0.9);">Total Tools</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools" style="color: rgba(255,255,255,0.3); font-size: 70px;"></i>
                </div>
                <a href="{{ route('admin.tools.index') }}" class="small-box-footer"
                    style="background: rgba(0,0,0,0.1); color: #fff;">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box"
                style="background: linear-gradient(135deg, #FFB347, #F48C06); color: #fff; border-radius: 15px; overflow: hidden;">
                <div class="inner">
                    <h3 style="color: #fff; font-weight: 800;">{{ $totalLoans ?? 0 }}</h3>
                    <p style="color: rgba(255,255,255,0.9);">Total Loans</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding" style="color: rgba(255,255,255,0.3); font-size: 70px;"></i>
                </div>
                <a href="{{ route('admin.loans.index') }}" class="small-box-footer"
                    style="background: rgba(0,0,0,0.1); color: #fff;">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box"
                style="background: linear-gradient(135deg, #DC2F02, #B71C1C); color: #fff; border-radius: 15px; overflow: hidden;">
                <div class="inner">
                    <h3 style="color: #fff; font-weight: 800;">Rp {{ number_format($totalFines ?? 0, 0, ',', '.') }}</h3>
                    <p style="color: rgba(255,255,255,0.9);">Total Fines</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave" style="color: rgba(255,255,255,0.3); font-size: 70px;"></i>
                </div>
                <a href="{{ route('admin.fines.index') }}" class="small-box-footer"
                    style="background: rgba(0,0,0,0.1); color: #fff;">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row">
        <div class="col-md-6">
            <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(232, 93, 4, 0.1);">
                <div class="card-header" style="background: transparent; border-bottom: 2px solid #FFF3E8; padding: 20px;">
                    <h3 class="card-title" style="font-weight: 700; color: #1a1a2e;">
                        <i class="fas fa-chart-pie me-2" style="color: #E85D04;"></i> Status Peminjaman
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="loanStatusChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(232, 93, 4, 0.1);">
                <div class="card-header" style="background: transparent; border-bottom: 2px solid #FFF3E8; padding: 20px;">
                    <h3 class="card-title" style="font-weight: 700; color: #1a1a2e;">
                        <i class="fas fa-chart-bar me-2" style="color: #E85D04;"></i> Peminjaman Per Bulan
                    </h3>
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
            <div class="card"
                style="border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(232, 93, 4, 0.1); background: linear-gradient(135deg, #FFF8F0, #FFF3E8);">
                <div class="card-body" style="padding: 30px;">
                    <div class="d-flex align-items-center">
                        <div
                            style="width: 60px; height: 60px; background: linear-gradient(135deg, #E85D04, #DC2F02); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 20px;">
                            <i class="fas fa-user-shield" style="color: #fff; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h4 style="font-weight: 800; color: #1a1a2e; margin: 0;">Welcome to Admin Dashboard</h4>
                            <p style="color: #666; margin: 5px 0 0;">
                                Selamat datang, <strong style="color: #E85D04;">{{ auth()->user()->name }}</strong>!
                                Anda login sebagai <span class="badge"
                                    style="background: #E85D04;">{{ auth()->user()->role }}</span>
                            </p>
                        </div>
                    </div>
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
                            backgroundColor: ['#FFB347', '#E85D04', '#F48C06', '#DC2F02', '#B71C1C'],
                            borderWidth: 3,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: { family: 'Inter', weight: '600' },
                                    padding: 15
                                }
                            }
                        },
                        cutout: '65%'
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
                            backgroundColor: 'rgba(232, 93, 4, 0.8)',
                            borderColor: '#E85D04',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, font: { family: 'Inter' } },
                                grid: { color: 'rgba(232, 93, 4, 0.05)' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Inter', weight: '600' } }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }
        });
    </script>
@endpush

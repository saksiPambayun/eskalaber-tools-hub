@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

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
            <div class="card" style="height: 400px;"> <!-- KUNCI 1: Tinggi Card Dibatasi -->
                <div class="card-header">
                    <h3 class="card-title">Status Peminjaman</h3>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <!-- KUNCI 2: Container Canvas dibikin relative dan fix height -->
                    <div style="position: relative; height: 100%; width: 100%;">
                        <canvas id="loanStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" style="height: 400px;"> <!-- KUNCI 1: Tinggi Card Dibatasi -->
                <div class="card-header">
                    <h3 class="card-title">Peminjaman Per Bulan</h3>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <!-- KUNCI 2: Container Canvas dibikin relative dan fix height -->
                    <div style="position: relative; height: 100%; width: 100%;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fine Rate + Welcome -->
    <div class="row mt-3">
        <div class="col-md-5">
            <div class="card card-outline card-danger">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-gavel mr-2"></i>Tarif Denda Global</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>@endif
                    @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                    <p class="mb-2">Tarif saat ini: <strong>Rp
                            {{ number_format($fineRate ?? 10000, 0, ',', '.') }}/hari</strong></p>
                    <form action="{{ route('admin.fine-setting.update') }}" method="POST" class="mb-2">
                        @csrf
                        <div class="input-group">
                            <input type="number" name="rate_per_day" min="0" max="1000000" step="1" class="form-control"
                                value="{{ old('rate_per_day', $fineRate ?? 10000) }}" required>
                            <div class="input-group-append">
                                <button class="btn btn-danger">Simpan</button>
                            </div>
                        </div>
                        <small class="text-muted">Berlaku untuk semua alat & hanya denda baru. Batas Rp
                            0–1.000.000/hari.</small>
                    </form>
                    <a href="{{ route('admin.fine-setting.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-history"></i> Lihat Riwayat Tarif
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card" style="height: 100%;">
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
                        maintainAspectRatio: false, // KUNCI 3: Biar chart ngikutin container, bukan layar
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
                        maintainAspectRatio: false, // KUNCI 3: Biar chart ngikutin container, bukan layar
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
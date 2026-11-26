@extends('layouts.app')

@section('title', 'Petugas Alat - Peminjaman')
@section('page-title', 'Manajemen Peminjaman')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('toolsman.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dasbor</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.loans.index') }}" class="nav-link active">
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
        <a href="{{ route('toolsman.payment-methods.index') }}" class="nav-link {{ request()->routeIs('toolsman.payment-methods.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-credit-card"></i>
            <p>Metode Pembayaran</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.fine-setting.index') }}" class="nav-link {{ request()->routeIs('toolsman.fine-setting.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-gavel"></i>
            <p>Pengaturan Pembayaran</p>
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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Peminjaman</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Status -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="btn-group" role="group">
                        <a href="{{ route('toolsman.loans.index', ['status' => 'all']) }}"
                            class="btn btn-sm {{ $status == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-list"></i> Semua
                        </a>
                        <a href="{{ route('toolsman.loans.index', ['status' => 'pending']) }}"
                            class="btn btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                            <i class="fas fa-clock"></i> Menunggu
                        </a>
                        <a href="{{ route('toolsman.loans.index', ['status' => 'approved']) }}"
                            class="btn btn-sm {{ $status == 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-check"></i> Disetujui
                        </a>
                        <a href="{{ route('toolsman.loans.index', ['status' => 'cancelled']) }}"
                            class="btn btn-sm {{ $status == 'cancelled' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                            <i class="fas fa-ban"></i> Dibatalkan
                        </a>
                        <a href="{{ route('toolsman.loans.index', ['status' => 'returned']) }}"
                            class="btn btn-sm {{ $status == 'returned' ? 'btn-success' : 'btn-outline-success' }}">
                            <i class="fas fa-undo"></i> Dikembalikan
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-muted">Total: {{ $loans->total() }} data</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pengguna</th>
                            <th>Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $index => $loan)
                            <tr>
                                <td>{{ $loans->firstItem() + $index }}</td>
                                <td>{{ $loan->user->name ?? '-' }}</td>
                                <td>{{ $loan->tool->name ?? '-' }}</td>
                                <td>{{ $loan->loan_date->format('d M Y') }}</td>
                                <td>{{ $loan->return_date->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $badge = match($loan->status) {
                                            'pending' => 'warning',
                                            'approved', 'borrowed' => 'primary',
                                            'returned' => 'success',
                                            'rejected', 'cancelled' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ match($loan->status) { 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'borrowed' => 'Dipinjam', 'returned' => 'Dikembalikan', 'cancelled' => 'Dibatalkan', 'rejected' => 'Ditolak', default => ucfirst($loan->status) } }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('toolsman.loans.detail', $loan->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data peminjaman</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <span class="text-muted">
                        Menampilkan {{ $loans->firstItem() ?? 0 }} - {{ $loans->lastItem() ?? 0 }}
                        dari {{ $loans->total() }} data
                    </span>
                </div>
                <div class="col-md-6 text-right">
                    {{ $loans->appends(['status' => $status])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
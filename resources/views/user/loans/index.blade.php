@extends('layouts.app')

@section('title', 'Peminjaman Saya')
@section('page-title', 'Peminjaman Saya')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('user.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dasbor</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.tools.index') }}" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>Alat</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.loans.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>Peminjaman Saya</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.fines.index') }}" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Pembayaran Saya</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Peminjaman Saya</h3>
            <div class="card-tools">
                <a href="{{ route('user.loans.add') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Pinjam Alat
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
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
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $loan->tool->name }}</td>
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
                                <a href="{{ route('user.loans.detail', $loan->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($loan->status == 'pending')
                                    <form action="{{ route('user.loans.cancel', $loan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin batalkan?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

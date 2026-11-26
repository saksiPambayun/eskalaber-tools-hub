@extends('layouts.app')

@section('title', 'Pembayaran Saya')
@section('page-title', 'Pembayaran Saya')

@section('sidebar')
    <li class="nav-item"><a href="{{ route('user.dashboard') }}" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dasbor</p></a></li>
    <li class="nav-item"><a href="{{ route('user.tools.index') }}" class="nav-link"><i class="nav-icon fas fa-tools"></i><p>Alat</p></a></li>
    <li class="nav-item"><a href="{{ route('user.loans.index') }}" class="nav-link"><i class="nav-icon fas fa-hand-holding"></i><p>Peminjaman Saya</p></a></li>
    <li class="nav-item"><a href="{{ route('user.fines.index') }}" class="nav-link active"><i class="nav-icon fas fa-money-bill-wave"></i><p>Pembayaran Saya</p></a></li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header"><h3 class="card-title">Daftar Pembayaran Saya</h3></div>
        <div class="card-body">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

            <div class="alert alert-info">
                <strong>Total Pembayaran Belum Lunas:</strong>
                Rp {{ number_format($fines->whereIn('status', ['unpaid', 'pending_verification', 'rejected'])->sum('amount'), 0, ',', '.') }}
            </div>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pinjaman / Alat</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fines as $index => $fine)
                        @php
                            $badge = match($fine->status) {
                                'unpaid' => 'danger',
                                'pending_verification' => 'warning',
                                'paid' => 'success',
                                'rejected' => 'dark',
                                default => 'secondary',
                            };
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>#{{ $fine->loan_id }} - {{ $fine->loan->tool->name ?? '-' }}</td>
                            <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $badge }}">{{ match($fine->status) { 'unpaid' => 'Belum Lunas', 'pending_verification' => 'Menunggu Verifikasi', 'paid' => 'Lunas', 'rejected' => 'Ditolak', default => str_replace('_', ' ', ucfirst($fine->status)) } }}</span></td>
                            <td>
                                <a href="{{ route('user.fines.detail', $fine->id) }}" class="btn btn-sm btn-primary">
                                    {{ in_array($fine->status, ['unpaid','rejected']) ? 'Bayar / Unggah Bukti' : 'Detail' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Belum ada pembayaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

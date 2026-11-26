@extends('layouts.app')

@section('title', 'Detail Pinjaman')
@section('page-title', 'Detail Peminjaman')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('toolsman.dashboard') }}" class="nav-link">
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
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail Peminjaman</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Pengguna</th>
                            <td>{{ $loan->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alat</th>
                            <td>{{ $loan->tool->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <td>{{ $loan->loan_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kembali</th>
                            <td>{{ $loan->return_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kembali Aktual</th>
                            <td>{{ $loan->actual_return_date ? $loan->actual_return_date->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    class="badge bg-{{ $loan->status == 'pending' ? 'warning' : ($loan->status == 'approved' ? 'primary' : ($loan->status == 'borrowed' ? 'info' : ($loan->status == 'returned' ? 'success' : 'danger'))) }}">
                                    {{ match($loan->status) { 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'borrowed' => 'Dipinjam', 'returned' => 'Dikembalikan', 'cancelled' => 'Dibatalkan', 'rejected' => 'Ditolak', default => ucfirst($loan->status) } }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $loan->notes ?? '-' }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        @if($loan->status == 'pending')
                            <form action="{{ route('toolsman.loans.approve', $loan->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i> Setujui
                                </button>
                            </form>
                            <form action="{{ route('toolsman.loans.reject', $loan->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-2"></i> Tolak
                                </button>
                            </form>
                        @endif

                        @if($loan->status == 'approved' || $loan->status == 'borrowed')
                            <form action="{{ route('toolsman.loans.returned', $loan->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning"
                                    onclick="return confirm('Yakin alat sudah dikembalikan?')">
                                    <i class="fas fa-undo me-2"></i> Dikembalikan
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('toolsman.loans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
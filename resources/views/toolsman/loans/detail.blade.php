@extends('layouts.app')

@section('title', 'Detail Pinjaman')
@section('page-title', 'Detail Peminjaman')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail Peminjaman</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>@endif
                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>@endif
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

                        @if(in_array($loan->status, ['approved', 'borrowed', 'late']))
                            <form action="{{ route('toolsman.loans.returned', $loan->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning"
                                    onclick="return confirm('Yakin alat sudah dikembalikan?')">
                                    <i class="fas fa-undo me-2"></i> Dikembalikan
                                </button>
                            </form>

                            @if(app()->environment(['local', 'testing']))
                                <form action="{{ route('toolsman.loans.test-return', $loan->id) }}" method="POST"
                                    class="d-inline-block ml-2">
                                    @csrf
                                    <div class="input-group input-group-sm" style="width: 320px;">
                                        <input type="date" name="actual_return_date" class="form-control"
                                            value="{{ optional($loan->return_date)->copy()->addDays(2)?->toDateString() }}"
                                            required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger"
                                                onclick="return confirm('Anda yakin membuat pembayaran untuk pengguna ini?')">
                                                Pembayaran
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
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
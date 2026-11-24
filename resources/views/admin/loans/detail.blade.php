@extends('layouts.app')

@section('title', 'Detail Loan')
@section('page-title', 'Detail Peminjaman')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail Peminjaman</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">User</th>
                            <td>{{ $loan->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tool</th>
                            <td>{{ $loan->tool->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <td>{{ $loan->loan_date?->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kembali</th>
                            <td>{{ $loan->return_date?->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dikembalikan</th>
                            <td>{{ $loan->actual_return_date?->format('d M Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $badge = match($loan->status) {
                                        'pending' => 'warning',
                                        'approved', 'borrowed' => 'primary',
                                        'returned' => 'success',
                                        'rejected', 'cancelled' => 'danger',
                                        'late' => 'dark',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($loan->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $loan->notes ?? '-' }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        @if($loan->status == 'pending')
                            <form action="{{ route('admin.loans.update-status', $loan->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.loans.update-status', $loan->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-2"></i> Reject
                                </button>
                            </form>
                        @endif

                        @if(in_array($loan->status, ['approved', 'borrowed', 'late']))
                            <form action="{{ route('admin.loans.update-status', $loan->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="returned">
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Yakin alat sudah dikembalikan?')">
                                    <i class="fas fa-undo me-2"></i> Returned
                                </button>
                            </form>

                            @if(app()->environment(['local', 'testing']))
                                <form action="{{ route('admin.loans.test-return', $loan->id) }}" method="POST" class="d-inline-block ml-2">
                                    @csrf
                                    <div class="input-group input-group-sm" style="width: 320px;">
                                        <input type="date" name="actual_return_date" class="form-control"
                                            value="{{ optional($loan->return_date)->copy()->addDays(2)?->toDateString() }}" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger" onclick="return confirm('Simulasikan pengembalian pada tanggal ini? Denda akan dibuat otomatis jika terlambat.')">
                                                Simulasi Return + Denda
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        @endif

                        <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

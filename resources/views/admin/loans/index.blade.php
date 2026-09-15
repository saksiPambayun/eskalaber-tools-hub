@extends('layouts.app')

@section('title', 'Loan Management')
@section('page-title', 'Loan Management')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-header">MANAGEMENT</li>
    <li class="nav-item">
        <a href="{{ route('admin.loans.index') }}" class="nav-link active">
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
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Peminjaman</h3>
        <div class="card-tools">
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-file-excel"></i> Export
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.loans.export-csv') }}" class="dropdown-item">
                        <i class="fas fa-file-csv"></i> Semua Data
                    </a>
                    <a href="{{ route('admin.loans.export-csv') }}?status=pending" class="dropdown-item">
                        <i class="fas fa-file-csv"></i> Pending
                    </a>
                    <a href="{{ route('admin.loans.export-csv') }}?status=approved" class="dropdown-item">
                        <i class="fas fa-file-csv"></i> Approved
                    </a>
                    <a href="{{ route('admin.loans.export-csv') }}?status=borrowed" class="dropdown-item">
                        <i class="fas fa-file-csv"></i> Borrowed
                    </a>
                    <a href="{{ route('admin.loans.export-csv') }}?status=returned" class="dropdown-item">
                        <i class="fas fa-file-csv"></i> Returned
                    </a>
                </div>
            </div>
        </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Status -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.loans.index', ['status' => 'all']) }}"
                            class="btn btn-sm {{ $status == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-list"></i> All
                        </a>
                        <a href="{{ route('admin.loans.index', ['status' => 'pending']) }}"
                            class="btn btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                            <i class="fas fa-clock"></i> Pending
                        </a>
                        <a href="{{ route('admin.loans.index', ['status' => 'approved']) }}"
                            class="btn btn-sm {{ $status == 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-check"></i> Approved
                        </a>
                        <a href="{{ route('admin.loans.index', ['status' => 'borrowed']) }}"
                            class="btn btn-sm {{ $status == 'borrowed' ? 'btn-info' : 'btn-outline-info' }}">
                            <i class="fas fa-hand-holding"></i> Borrowed
                        </a>
                        <a href="{{ route('admin.loans.index', ['status' => 'returned']) }}"
                            class="btn btn-sm {{ $status == 'returned' ? 'btn-success' : 'btn-outline-success' }}">
                            <i class="fas fa-undo"></i> Returned
                        </a>
                        <a href="{{ route('admin.loans.index', ['status' => 'rejected']) }}"
                            class="btn btn-sm {{ $status == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                            <i class="fas fa-times"></i> Rejected
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-muted">Total: {{ $loans->total() }} data</span>
                </div>
            </div>

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Tool</th>
                            <th>Loan Date</th>
                            <th>Return Date</th>
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
                                    <span
                                        class="badge bg-{{ $loan->status == 'pending' ? 'warning' : ($loan->status == 'approved' ? 'primary' : ($loan->status == 'borrowed' ? 'info' : ($loan->status == 'returned' ? 'success' : 'danger'))) }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.loans.detail', $loan->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.loans.delete', $loan->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
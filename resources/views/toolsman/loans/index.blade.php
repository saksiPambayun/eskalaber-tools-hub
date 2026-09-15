@extends('layouts.app')

@section('title', 'Toolsman - Loans')
@section('page-title', 'Loan Management')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('toolsman.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.loans.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>Loans</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.fines.index') }}" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Fines</p>
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

            <!-- Filter Status -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="btn-group" role="group">
                        <a href="{{ route('toolsman.loans.index', ['status' => 'all']) }}"
                            class="btn btn-sm {{ $status == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-list"></i> All
                        </a>
                        <a href="{{ route('toolsman.loans.index', ['status' => 'pending']) }}"
                            class="btn btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                            <i class="fas fa-clock"></i> Pending
                        </a>
                        <a href="{{ route('toolsman.loans.index', ['status' => 'approved']) }}"
                            class="btn btn-sm {{ $status == 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-check"></i> Approved
                        </a>
                        <a href="{{ route('toolsman.loans.index', ['status' => 'borrowed']) }}"
                            class="btn btn-sm {{ $status == 'borrowed' ? 'btn-info' : 'btn-outline-info' }}">
                            <i class="fas fa-hand-holding"></i> Borrowed
                        </a>
                        <a href="{{ route('toolsman.loans.index', ['status' => 'returned']) }}"
                            class="btn btn-sm {{ $status == 'returned' ? 'btn-success' : 'btn-outline-success' }}">
                            <i class="fas fa-undo"></i> Returned
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
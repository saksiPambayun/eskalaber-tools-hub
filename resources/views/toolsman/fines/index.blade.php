@extends('layouts.app')

@section('title', 'Toolsman - Fines')
@section('page-title', 'Fine Management')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('toolsman.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.loans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>Loans</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('toolsman.fines.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Fines</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Denda</h3>
            <div class="card-tools">
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-file-excel"></i> Export
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('toolsman.fines.export') }}" class="dropdown-item">
                            <i class="fas fa-file-excel"></i> Semua Data
                        </a>
                        <a href="{{ route('toolsman.fines.export') }}?status=unpaid" class="dropdown-item">
                            <i class="fas fa-file-excel"></i> Unpaid
                        </a>
                        <a href="{{ route('toolsman.fines.export') }}?status=paid" class="dropdown-item">
                            <i class="fas fa-file-excel"></i> Paid
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
                        <a href="{{ route('toolsman.fines.index', ['status' => 'all']) }}"
                            class="btn btn-sm {{ $status == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-list"></i> All
                        </a>
                        <a href="{{ route('toolsman.fines.index', ['status' => 'unpaid']) }}"
                            class="btn btn-sm {{ $status == 'unpaid' ? 'btn-danger' : 'btn-outline-danger' }}">
                            <i class="fas fa-clock"></i> Unpaid
                        </a>
                        <a href="{{ route('toolsman.fines.index', ['status' => 'paid']) }}"
                            class="btn btn-sm {{ $status == 'paid' ? 'btn-success' : 'btn-outline-success' }}">
                            <i class="fas fa-check"></i> Paid
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-muted">Total: {{ $fines->total() }} data</span>
                </div>
            </div>

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Loan</th>
                            <th>Amount</th>
                            <th>Late Days</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fines as $index => $fine)
                            <tr>
                                <td>{{ $fines->firstItem() + $index }}</td>
                                <td>{{ $fine->user->name ?? '-' }}</td>
                                <td>#{{ $fine->loan_id }}</td>
                                <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                                <td>{{ $fine->late_days }} hari</td>
                                <td>
                                    <span class="badge bg-{{ $fine->status == 'unpaid' ? 'danger' : 'success' }}">
                                        {{ ucfirst($fine->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($fine->status == 'unpaid')
                                        <form action="{{ route('toolsman.fines.paid', $fine->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Bayar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data denda</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <span class="text-muted">
                        Menampilkan {{ $fines->firstItem() ?? 0 }} - {{ $fines->lastItem() ?? 0 }}
                        dari {{ $fines->total() }} data
                    </span>
                </div>
                <div class="col-md-6 text-right">
                    {{ $fines->appends(['status' => $status])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
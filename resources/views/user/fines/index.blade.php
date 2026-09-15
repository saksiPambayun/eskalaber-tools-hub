@extends('layouts.app')

@section('title', 'My Fines')
@section('page-title', 'My Fines')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('user.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.tools.index') }}" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>Tools</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.loans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>My Loans</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.fines.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>My Fines</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Denda Saya</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <strong>Total Denda Belum Dibayar:</strong>
                        Rp {{ number_format($fines->where('status', 'unpaid')->sum('amount'), 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Loan</th>
                        <th>Amount</th>
                        <th>Late Days</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fines as $index => $fine)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>#{{ $fine->loan_id }}</td>
                            <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                            <td>{{ $fine->late_days }} hari</td>
                            <td>
                                <span class="badge bg-{{ $fine->status == 'unpaid' ? 'danger' : 'success' }}">
                                    {{ ucfirst($fine->status) }}
                                </span>
                            </td>
                            <td>{{ $fine->payment_date ? $fine->payment_date->format('d M Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada denda</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
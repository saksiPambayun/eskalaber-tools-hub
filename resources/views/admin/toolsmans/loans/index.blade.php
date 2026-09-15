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
                    @forelse($loans as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ $item->tool->name ?? '-' }}</td>
                            <td>{{ $item->loan_date->format('d M Y') }}</td>
                            <td>{{ $item->return_date->format('d M Y') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $item->status == 'pending' ? 'warning' : ($item->status == 'approved' ? 'primary' : ($item->status == 'borrowed' ? 'info' : ($item->status == 'returned' ? 'success' : 'danger'))) }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('toolsman.loans.detail', $item->id) }}" class="btn btn-info btn-sm">
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
    </div>
@endsection
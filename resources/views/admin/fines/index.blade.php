@extends('layouts.app')

@section('title', 'Fine Management')
@section('page-title', 'Fine Management')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-header">MANAGEMENT</li>
    <li class="nav-item">
        <a href="{{ route('admin.loans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>Loans</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.fines.index') }}" class="nav-link active">
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
            <!-- Tombol Export -->
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-file-excel"></i> Export
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.fines.export-csv') }}" class="dropdown-item">
                        <i class="fas fa-file-csv"></i> Semua Data
                    </a>
                    <a href="{{ route('admin.fines.export-csv') }}?status=unpaid" class="dropdown-item">
                        <i class="fas fa-file-csv"></i> Unpaid
                    </a>
                    <a href="{{ route('admin.fines.export-csv') }}?status=paid" class="dropdown-item">
                        <i class="fas fa-file-csv"></i> Paid
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
                    <a href="{{ route('admin.fines.index', ['status' => 'all']) }}"
                        class="btn btn-sm {{ $status == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-list"></i> All
                    </a>
                    <a href="{{ route('admin.fines.index', ['status' => 'unpaid']) }}"
                        class="btn btn-sm {{ $status == 'unpaid' ? 'btn-danger' : 'btn-outline-danger' }}">
                        <i class="fas fa-clock"></i> Unpaid
                    </a>
                    <a href="{{ route('admin.fines.index', ['status' => 'paid']) }}"
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
                        <th>Status</th>
                        <th>Payment Date</th>
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
                            <td>
                                <span class="badge bg-{{ $fine->status == 'unpaid' ? 'danger' : 'success' }}">
                                    {{ ucfirst($fine->status) }}
                                </span>
                            </td>
                            <td>{{ $fine->payment_date ? $fine->payment_date->format('d M Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.fines.detail', $fine->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($fine->status == 'unpaid')
                                    <form action="{{ route('admin.fines.update-status', $fine->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Bayar
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.fines.delete', $fine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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

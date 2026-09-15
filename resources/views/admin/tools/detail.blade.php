@extends('layouts.app')

@section('title', 'Detail Tool')
@section('page-title', 'Detail Tool')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-header">MANAGEMENT</li>
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Users</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.toolsmans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Toolsman</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.loans.index') }}" class="nav-link">
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
    <li class="nav-header">MASTER DATA</li>
    <li class="nav-item">
        <a href="{{ route('admin.departments.index') }}" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>Departments</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.places.index') }}" class="nav-link">
            <i class="nav-icon fas fa-map-marker-alt"></i>
            <p>Places</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.types.index') }}" class="nav-link">
            <i class="nav-icon fas fa-tag"></i>
            <p>Types</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.categories.index') }}" class="nav-link">
            <i class="nav-icon fas fa-folder"></i>
            <p>Categories</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.tools.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-tools"></i>
            <p>Tools</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($tool->image)
                        <img src="{{ asset($tool->image) }}" class="img-fluid" style="max-height: 300px; border-radius: 10px;">
                    @else
                        <i class="fas fa-tools fa-8x text-muted"></i>
                    @endif
                </div>
            </div>
            @if($tool->qr_code)
                <div class="card mt-3">
                    <div class="card-body text-center">
                        <h6>QR Code</h6>
                        <img src="{{ asset($tool->qr_code) }}" style="max-width: 150px;">
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail Tool</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Nama</th>
                            <td>{{ $tool->name }}</td>
                        </tr>
                        <tr>
                            <th>Kode</th>
                            <td>{{ $tool->code }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $tool->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $tool->category->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ $tool->type->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Place</th>
                            <td>{{ $tool->place->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Stock</th>
                            <td>{{ $tool->stock }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    class="badge bg-{{ $tool->status == 'available' ? 'success' : ($tool->status == 'borrowed' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($tool->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $tool->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <a href="{{ route('admin.tools.update', $tool->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('admin.tools.generate-qr', $tool->id) }}" class="btn btn-info">
                            <i class="fas fa-qrcode me-2"></i> Generate QR
                        </a>
                        <a href="{{ route('admin.tools.preview-qr', $tool->id) }}" class="btn btn-secondary">
                            <i class="fas fa-eye me-2"></i> Preview QR
                        </a>
                        <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
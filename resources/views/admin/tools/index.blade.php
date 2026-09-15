@extends('layouts.app')

@section('title', 'Tools')
@section('page-title', 'Tools Management')

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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Tools</h3>
            <div class="card-tools">
                <a href="{{ route('admin.tools.add') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Tool
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Image</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>QR</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tools as $index => $tool)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($tool->image)
                                        <img src="{{ asset($tool->image) }}" width="50" height="50"
                                            style="object-fit: cover; border-radius: 5px;">
                                    @else
                                        <i class="fas fa-tools fa-2x text-muted"></i>
                                    @endif
                                </td>
                                <td>{{ $tool->code }}</td>
                                <td>{{ $tool->name }}</td>
                                <td>{{ $tool->category->name ?? '-' }}</td>
                                <td>{{ $tool->stock }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $tool->status == 'available' ? 'success' : ($tool->status == 'borrowed' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($tool->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($tool->qr_code)
                                        <a href="{{ route('admin.tools.preview-qr', $tool->id) }}" class="btn btn-info btn-sm"
                                            target="_blank">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('admin.tools.generate-qr', $tool->id) }}"
                                            class="btn btn-secondary btn-sm">
                                            <i class="fas fa-plus"></i> Generate
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.tools.detail', $tool->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tools.update', $tool->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tools.delete', $tool->id) }}" method="POST" class="d-inline"
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
                                <td colspan="9" class="text-center">Belum ada data tool</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
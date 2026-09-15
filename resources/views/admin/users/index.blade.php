@extends('layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-header">MANAGEMENT</li>
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link active">
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
        <a href="{{ route('admin.tools.index') }}" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>Tools</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Users</h3>
            <div class="card-tools">
                <a href="{{ route('admin.users.add') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah User
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

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Phone</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->department->name ?? '-' }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.update', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus data ini?')">
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
                            <td colspan="6" class="text-center">Belum ada data user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
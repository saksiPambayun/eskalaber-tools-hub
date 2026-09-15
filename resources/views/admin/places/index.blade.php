@extends('layouts.app')

@section('title', 'Places')
@section('page-title', 'Places Management')

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
    <li class="nav-header">MASTER DATA</li>
    <li class="nav-item">
        <a href="{{ route('admin.departments.index') }}" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>Departments</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.places.index') }}" class="nav-link active">
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
        <h3 class="card-title">Daftar Places</h3>
        <div class="card-tools">
            <a href="{{ route('admin.places.add') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Place
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Place</th>
                    <th>Created At</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($places as $index => $place)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $place->name }}</td>
                    <td>{{ $place->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.places.update', $place->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.places.delete', $place->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

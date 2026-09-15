@extends('layouts.app')

@section('title', 'Toolsman Management')
@section('page-title', 'Toolsman Management')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Users</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.toolsmans.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Toolsman</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.departments.index') }}" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>Departments</p>
        </a>
    </li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Toolsman</h3>
        <div class="card-tools">
            <a href="{{ route('admin.toolsmans.add') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Toolsman
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
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($toolsmans as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->department->name ?? '-' }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.toolsmans.detail', $user->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.toolsmans.update', $user->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.toolsmans.delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>@extends('layouts.app')

@section('title', 'Toolsman Management')
@section('page-title', 'Toolsman Management')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Users</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.toolsmans.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Toolsman</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.departments.index') }}" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>Departments</p>
        </a>
    </li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Toolsman</h3>
        <div class="card-tools">
            <a href="{{ route('admin.toolsmans.add') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Toolsman
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
                @forelse($toolsmans as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->department->name ?? '-' }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.toolsmans.detail', $user->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.toolsmans.update', $user->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.toolsmans.delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
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
                    <td colspan="6" class="text-center">Belum ada data toolsman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@endsection

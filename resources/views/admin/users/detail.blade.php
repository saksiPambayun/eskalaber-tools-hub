@extends('layouts.app')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail User</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Nama</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td><span class="badge bg-primary">{{ $user->role }}</span></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ $user->department->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td>{{ $user->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $user->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bergabung Sejak</th>
                            <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <a href="{{ route('admin.users.update', $user->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
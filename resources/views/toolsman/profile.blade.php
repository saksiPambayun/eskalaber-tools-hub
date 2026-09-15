@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('toolsman.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profile Saya</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>{{ auth()->user()->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ auth()->user()->email }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td><span class="badge bg-success">{{ auth()->user()->role }}</span></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ auth()->user()->department->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ auth()->user()->phone ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
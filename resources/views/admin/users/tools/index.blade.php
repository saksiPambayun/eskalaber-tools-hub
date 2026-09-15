@extends('layouts.app')

@section('title', 'Tools')
@section('page-title', 'Daftar Tools')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('user.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.tools.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-tools"></i>
            <p>Tools</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Tools</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($tools as $tool)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                @if($tool->image)
                                    <img src="{{ asset($tool->image) }}" class="img-fluid"
                                        style="max-height: 150px; object-fit: cover;">
                                @else
                                    <i class="fas fa-tools fa-4x text-muted"></i>
                                @endif
                                <h6 class="mt-2">{{ $tool->name }}</h6>
                                <small class="text-muted">Stock: {{ $tool->stock }}</small>
                                <br>
                                <span class="badge bg-{{ $tool->status == 'available' ? 'success' : 'warning' }}">
                                    {{ ucfirst($tool->status) }}
                                </span>
                            </div>
                            <div class="card-footer text-center">
                                <a href="{{ route('user.tools.detail', $tool->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">Belum ada data tool</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
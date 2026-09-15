@extends('layouts.app')

@section('title', 'Detail Tool')
@section('page-title', 'Detail Tool')

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
    <li class="nav-item">
        <a href="{{ route('user.loans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>My Loans</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.fines.index') }}" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>My Fines</p>
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
                    </table>

                    <div class="mt-3">
                        @if($tool->status == 'available' && $tool->stock > 0)
                            <a href="{{ route('user.loans.add', ['tool_id' => $tool->id]) }}" class="btn btn-primary">
                                <i class="fas fa-hand-holding me-2"></i> Pinjam Alat
                            </a>
                        @endif
                        <a href="{{ route('user.tools.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
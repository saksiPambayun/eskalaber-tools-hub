@extends('layouts.app')

@section('title', 'Detail Alat')
@section('page-title', 'Detail Alat')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dasbor</p>
        </a>
    </li>
    <li class="nav-header">MANAJEMEN</li>
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Pengguna</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.toolsmans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Petugas Alat</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.loans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>Pinjaman</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.fines.index') }}" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Pembayaran</p>
        </a>
    </li>
    <li class="nav-header">DATA MASTER</li>
    <li class="nav-item">
        <a href="{{ route('admin.departments.index') }}" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>Departemen</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.places.index') }}" class="nav-link">
            <i class="nav-icon fas fa-map-marker-alt"></i>
            <p>Lokasi</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.categories.index') }}" class="nav-link">
            <i class="nav-icon fas fa-folder"></i>
            <p>Kategori</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.tools.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-tools"></i>
            <p>Alat</p>
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

        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail Alat</h5>
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
                            <th>Kategori</th>
                            <td>{{ $tool->category->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $tool->place->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td>{{ $tool->stock }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    class="badge bg-{{ $tool->status == 'available' ? 'success' : ($tool->status == 'borrowed' ? 'warning' : 'danger') }}">
                                    {{ match($tool->status) { 'available' => 'Tersedia', 'borrowed' => 'Dipinjam', 'broken' => 'Rusak', 'maintenance' => 'Perawatan', default => ucfirst($tool->status) } }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ $tool->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <a href="{{ route('admin.tools.update', $tool->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Ubah
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
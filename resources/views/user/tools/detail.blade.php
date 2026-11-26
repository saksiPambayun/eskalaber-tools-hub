@extends('layouts.app')

@section('title', 'Detail Alat')
@section('page-title', 'Detail Alat')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('user.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dasbor</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.tools.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-tools"></i>
            <p>Alat</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.loans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-hand-holding"></i>
            <p>Peminjaman Saya</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user.fines.index') }}" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Pembayaran Saya</p>
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
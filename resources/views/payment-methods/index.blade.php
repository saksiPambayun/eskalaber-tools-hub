@extends('layouts.app')

@section('title', 'Metode Pembayaran')
@section('page-title', 'Metode Pembayaran')

@if($panel === 'toolsman')
    @section('sidebar')
        <li class="nav-item"><a href="{{ route('toolsman.dashboard') }}" class="nav-link"><i
                    class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dasbor</p>
            </a></li>
        <li class="nav-item"><a href="{{ route('toolsman.loans.index') }}" class="nav-link"><i
                    class="nav-icon fas fa-hand-holding"></i>
                <p>Pinjaman</p>
            </a></li>
        <li class="nav-item"><a href="{{ route('toolsman.fines.index') }}" class="nav-link"><i
                    class="nav-icon fas fa-money-bill-wave"></i>
                <p>Pembayaran</p>
            </a></li>
        <li class="nav-item"><a href="{{ route('toolsman.payment-methods.index') }}" class="nav-link active"><i
                    class="nav-icon fas fa-credit-card"></i>
                <p>Metode Pembayaran</p>
            </a></li>
        <li class="nav-item"><a href="{{ route('toolsman.logo-setting.index') }}" class="nav-link"><i
                    class="nav-icon fas fa-image"></i>
                <p>Pengaturan Logo</p>
            </a></li>
    @endsection
@endif

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Daftar Metode Pembayaran</h3>
            <a href="{{ route($panel . '.payment-methods.add') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Metode
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>No. Rekening / Akun</th>
                            <th>Atas Nama</th>
                            <th>QR</th>
                            <th>Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($methods as $method)
                            <tr>
                                <td>{{ $method->name }}</td>
                                <td>{{ match($method->type) { 'bank' => 'BANK', 'ewallet' => 'DOMPET DIGITAL', 'qris' => 'QRIS', 'other' => 'LAINNYA', default => strtoupper($method->type) } }}</td>
                                <td>{{ $method->account_number ?? '-' }}</td>
                                <td>{{ $method->account_name ?? '-' }}</td>
                                <td>
                                    @if($method->qr_image)
                                        <a href="{{ $method->qrImageUrl() }}" target="_blank">Lihat</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $method->is_active ? 'success' : 'secondary' }}">
                                        {{ $method->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route($panel . '.payment-methods.edit', $method->id) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route($panel . '.payment-methods.delete', $method->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Hapus metode ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada metode pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
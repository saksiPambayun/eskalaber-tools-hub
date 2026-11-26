@extends('layouts.app')

@section('title', 'Petugas Alat - Pembayaran')
@section('page-title', 'Manajemen Pembayaran')

@section('sidebar')
    <li class="nav-item"><a href="{{ route('toolsman.dashboard') }}" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dasbor</p></a></li>
    <li class="nav-item"><a href="{{ route('toolsman.loans.index') }}" class="nav-link"><i class="nav-icon fas fa-hand-holding"></i><p>Pinjaman</p></a></li>
    <li class="nav-item"><a href="{{ route('toolsman.fines.index') }}" class="nav-link active"><i class="nav-icon fas fa-money-bill-wave"></i><p>Pembayaran</p></a></li>
    <li class="nav-item"><a href="{{ route('toolsman.payment-methods.index') }}" class="nav-link"><i class="nav-icon fas fa-credit-card"></i><p>Metode Pembayaran</p></a></li>
    <li class="nav-item"><a href="{{ route('toolsman.fine-setting.index') }}" class="nav-link {{ request()->routeIs('toolsman.fine-setting.*') ? 'active' : '' }}"><i class="nav-icon fas fa-gavel"></i><p>Pengaturan Pembayaran</p></a></li>
    <li class="nav-item"><a href="{{ route('toolsman.logo-setting.index') }}" class="nav-link"><i class="nav-icon fas fa-image"></i><p>Pengaturan Logo</p></a></li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pembayaran</h3>
        <div class="card-tools">
            <a href="{{ route('toolsman.fines.export') }}?status={{ $status }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Ekspor</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="mb-3">
            @foreach(['all' => 'Semua', 'unpaid' => 'Belum Lunas', 'pending_verification' => 'Menunggu', 'paid' => 'Lunas', 'rejected' => 'Ditolak'] as $key => $label)
                <a href="{{ route('toolsman.fines.index', ['status' => $key]) }}"
                   class="btn btn-sm {{ $status == $key ? 'btn-primary' : 'btn-outline-primary' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr><th>No</th><th>Pengguna</th><th>Pinjaman</th><th>Jumlah</th><th>Status</th><th>Bukti</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($fines as $index => $fine)
                        @php $badge = match($fine->status){'unpaid'=>'danger','pending_verification'=>'warning','paid'=>'success','rejected'=>'dark',default=>'secondary'}; @endphp
                        <tr>
                            <td>{{ $fines->firstItem() + $index }}</td>
                            <td>{{ $fine->user->name ?? '-' }}</td>
                            <td>#{{ $fine->loan_id }}</td>
                            <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $badge }}">{{ match($fine->status) { 'unpaid' => 'Belum Lunas', 'pending_verification' => 'Menunggu Verifikasi', 'paid' => 'Lunas', 'rejected' => 'Ditolak', default => str_replace('_',' ',ucfirst($fine->status)) } }}</span></td>
                            <td>@if($fine->proof_image)<a href="{{ $fine->proofImageUrl() }}" target="_blank">Lihat</a>@else - @endif</td>
                            <td>
                                <a href="{{ route('toolsman.fines.detail', $fine->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada data pembayaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $fines->appends(['status' => $status])->links() }}</div>
    </div>
</div>
@endsection

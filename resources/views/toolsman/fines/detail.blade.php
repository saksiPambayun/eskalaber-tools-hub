@extends('layouts.app')

@section('title', 'Detail Denda')
@section('page-title', 'Detail Denda')

@section('sidebar')
    <li class="nav-item"><a href="{{ route('toolsman.dashboard') }}" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
    <li class="nav-item"><a href="{{ route('toolsman.loans.index') }}" class="nav-link"><i class="nav-icon fas fa-hand-holding"></i><p>Loans</p></a></li>
    <li class="nav-item"><a href="{{ route('toolsman.fines.index') }}" class="nav-link active"><i class="nav-icon fas fa-money-bill-wave"></i><p>Fines</p></a></li>
    <li class="nav-item"><a href="{{ route('toolsman.payment-methods.index') }}" class="nav-link"><i class="nav-icon fas fa-credit-card"></i><p>Metode Pembayaran</p></a></li>
    <li class="nav-item"><a href="{{ route('toolsman.fine-setting.index') }}" class="nav-link {{ request()->routeIs('toolsman.fine-setting.*') ? 'active' : '' }}"><i class="nav-icon fas fa-gavel"></i><p>Pengaturan Denda</p></a></li>
    <li class="nav-item"><a href="{{ route('toolsman.logo-setting.index') }}" class="nav-link"><i class="nav-icon fas fa-image"></i><p>Pengaturan Logo</p></a></li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Detail Denda #{{ $fine->id }}</h5></div>
            <div class="card-body">
                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                <table class="table table-bordered">
                    <tr><th style="width:220px;">User</th><td>{{ $fine->user->name ?? '-' }}</td></tr>
                    <tr><th>Peminjaman</th><td>#{{ $fine->loan_id }} - {{ $fine->loan->tool->name ?? '-' }}</td></tr>
                    <tr><th>Jumlah Denda</th><td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td></tr>
                    <tr><th>Keterlambatan</th><td>{{ $fine->late_days ?? 0 }} hari</td></tr>
                    <tr><th>Status</th>
                        <td>
                            @php $badge = match($fine->status){'unpaid'=>'danger','pending_verification'=>'warning','paid'=>'success','rejected'=>'dark',default=>'secondary'}; @endphp
                            <span class="badge bg-{{ $badge }}">{{ str_replace('_',' ',ucfirst($fine->status)) }}</span>
                        </td>
                    </tr>
                    <tr><th>Metode Pembayaran</th><td>{{ $fine->paymentMethod->name ?? $fine->payment_method ?? '-' }}</td></tr>
                    <tr><th>Referensi</th><td>{{ $fine->payment_reference ?? '-' }}</td></tr>
                    <tr><th>Tanggal Submit</th><td>{{ $fine->submitted_at?->format('d M Y H:i') ?? '-' }}</td></tr>
                    <tr><th>Tanggal Bayar</th><td>{{ $fine->payment_date?->format('d M Y') ?? '-' }}</td></tr>
                    <tr><th>Catatan Verifikasi</th><td>{{ $fine->review_note ?? '-' }}</td></tr>
                </table>

                @if($fine->proof_image)
                    <div class="mb-3">
                        <strong>Bukti Pembayaran:</strong><br>
                        <a href="{{ $fine->proofImageUrl() }}" target="_blank">
                            <img src="{{ $fine->proofImageUrl() }}" alt="Bukti pembayaran" style="max-width:100%;max-height:360px;border:1px solid #ddd;border-radius:8px;">
                        </a>
                    </div>
                @endif

                @if($fine->status === 'pending_verification')
                    <div class="border rounded p-3 mb-3">
                        <h6>Verifikasi Pembayaran</h6>
                        <form action="{{ route('toolsman.fines.verify', $fine->id) }}" method="POST" class="d-inline-block mr-2">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <div class="form-group mb-2">
                                <input type="text" name="review_note" class="form-control" placeholder="Catatan (opsional)">
                            </div>
                            <button class="btn btn-success"><i class="fas fa-check"></i> Terima</button>
                        </form>
                        <form action="{{ route('toolsman.fines.verify', $fine->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <div class="form-group mb-2">
                                <input type="text" name="review_note" class="form-control" placeholder="Alasan penolakan (opsional)">
                            </div>
                            <button class="btn btn-danger"><i class="fas fa-times"></i> Tolak</button>
                        </form>
                    </div>
                @endif

                <a href="{{ route('toolsman.fines.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection

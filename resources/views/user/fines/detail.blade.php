@extends('layouts.app')

@section('title', 'Detail Pembayaran')
@section('page-title', 'Detail Pembayaran')

@section('sidebar')
    <li class="nav-item"><a href="{{ route('user.dashboard') }}" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dasbor</p></a></li>
    <li class="nav-item"><a href="{{ route('user.tools.index') }}" class="nav-link"><i class="nav-icon fas fa-tools"></i><p>Alat</p></a></li>
    <li class="nav-item"><a href="{{ route('user.loans.index') }}" class="nav-link"><i class="nav-icon fas fa-hand-holding"></i><p>Peminjaman Saya</p></a></li>
    <li class="nav-item"><a href="{{ route('user.fines.index') }}" class="nav-link active"><i class="nav-icon fas fa-money-bill-wave"></i><p>Pembayaran Saya</p></a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Informasi Pembayaran</h3></div>
            <div class="card-body">
                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                <table class="table table-sm table-bordered">
                    <tr><th>Pinjaman</th><td>#{{ $fine->loan_id }} - {{ $fine->loan->tool->name ?? '-' }}</td></tr>
                    <tr><th>Jumlah</th><td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td></tr>
                    <tr><th>Hari Telat</th><td>{{ $fine->late_days }} hari</td></tr>
                    <tr><th>Status</th>
                        <td>
                            @php
                                $badge = match($fine->status) {
                                    'unpaid' => 'danger',
                                    'pending_verification' => 'warning',
                                    'paid' => 'success',
                                    'rejected' => 'dark',
                                    'cancelled' => 'secondary',
                                    default => 'secondary',
                                };
                                $label = match($fine->status) { 'unpaid' => 'Belum Lunas', 'pending_verification' => 'Menunggu Verifikasi', 'paid' => 'Lunas', 'rejected' => 'Ditolak', default => str_replace('_', ' ', ucfirst($fine->status)) };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                        </td>
                    </tr>
                    <tr><th>Metode</th><td>{{ $fine->paymentMethod->name ?? $fine->payment_method ?? '-' }}</td></tr>
                    <tr><th>Referensi</th><td>{{ $fine->payment_reference ?? '-' }}</td></tr>
                    <tr><th>Dikirim</th><td>{{ $fine->submitted_at?->format('d M Y H:i') ?? '-' }}</td></tr>
                    <tr><th>Catatan Verifikator</th><td>{{ $fine->review_note ?? '-' }}</td></tr>
                </table>
                @if($fine->proof_image)
                    <div class="mt-2">
                        <strong>Bukti Pembayaran:</strong><br>
                        <a href="{{ $fine->proofImageUrl() }}" target="_blank">
                            <img src="{{ $fine->proofImageUrl() }}" alt="Bukti" style="max-width:100%;max-height:260px;border:1px solid #ddd;border-radius:8px;">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Lakukan Pembayaran</h3></div>
            <div class="card-body">
                @if(in_array($fine->status, ['unpaid', 'rejected']))
                    @if($fine->status === 'rejected')
                        <div class="alert alert-warning">Bukti pembayaran sebelumnya ditolak. Silakan unggah bukti baru.</div>
                    @endif

                    <div class="mb-3">
                        <strong>Metode Pembayaran Tersedia:</strong>
                        <div class="row mt-2">
                            @forelse($paymentMethods as $pm)
                                <div class="col-md-6 mb-2">
                                    <div class="border rounded p-2 h-100">
                                        <div class="font-weight-bold">{{ $pm->name }} <small class="text-muted">({{ match($pm->type) { 'bank' => 'BANK', 'ewallet' => 'DOMPET DIGITAL', 'qris' => 'QRIS', 'other' => 'LAINNYA', default => strtoupper($pm->type) } }})</small></div>
                                        @if($pm->account_number)<div>No: {{ $pm->account_number }}</div>@endif
                                        @if($pm->account_name)<div>A/N: {{ $pm->account_name }}</div>@endif
                                        @if($pm->qr_image)
                                            <a href="{{ $pm->qrImageUrl() }}" target="_blank"><img src="{{ $pm->qrImageUrl() }}" alt="QR" style="max-height:120px;margin-top:6px;"></a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-12"><div class="alert alert-info">Belum ada metode pembayaran aktif. Hubungi admin/petugas.</div></div>
                            @endforelse
                        </div>
                    </div>

                    <form action="{{ route('user.fines.pay', $fine->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Metode Pembayaran</label>
                            <select name="payment_method_id" class="form-control @error('payment_method_id') is-invalid @enderror" required>
                                <option value="">Pilih metode</option>
                                @foreach($paymentMethods as $pm)
                                    <option value="{{ $pm->id }}" {{ old('payment_method_id', $fine->payment_method_id) == $pm->id ? 'selected' : '' }}>
                                        {{ $pm->name }}{{ $pm->account_number ? ' - ' . $pm->account_number : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group mb-3">
                            <label>Referensi/No. Transaksi (opsional)</label>
                            <input type="text" name="payment_reference" class="form-control @error('payment_reference') is-invalid @enderror" value="{{ old('payment_reference', $fine->payment_reference) }}">
                            @error('payment_reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group mb-3">
                            <label>Bukti Pembayaran (PNG/JPG/JPEG, maks 2MB)</label>
                            <input type="file" name="proof_image" class="form-control-file @error('proof_image') is-invalid @enderror" accept=".png,.jpg,.jpeg,image/png,image/jpeg" required>
                            @error('proof_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-primary"><i class="fas fa-upload"></i> Kirim Bukti Pembayaran</button>
                    </form>
                @elseif($fine->status === 'pending_verification')
                    <div class="alert alert-info mb-0">Bukti pembayaran sudah dikirim dan sedang menunggu verifikasi admin/petugas.</div>
                @elseif($fine->status === 'paid')
                    <div class="alert alert-success mb-0">Pembayaran sudah lunas. Terima kasih.</div>
                @else
                    <div class="alert alert-secondary mb-0">Status pembayaran: {{ match($fine->status) { 'unpaid' => 'Belum Lunas', 'pending_verification' => 'Menunggu Verifikasi', 'paid' => 'Lunas', 'rejected' => 'Ditolak', default => $fine->status } }}.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

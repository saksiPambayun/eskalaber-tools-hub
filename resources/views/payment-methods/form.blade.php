@extends('layouts.app')

@section('title', $isEdit ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran')
@section('page-title', $isEdit ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran')

@if($panel === 'toolsman')
    @section('sidebar')
        <li class="nav-item"><a href="/" class="nav-link" target="_blank"><i class="nav-icon fas fa-globe"></i><p>Landing Page</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.dashboard') }}" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.loans.index') }}" class="nav-link"><i class="nav-icon fas fa-hand-holding"></i><p>Loans</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.fines.index') }}" class="nav-link"><i class="nav-icon fas fa-money-bill-wave"></i><p>Fines</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.payment-methods.index') }}" class="nav-link active"><i class="nav-icon fas fa-credit-card"></i><p>Metode Pembayaran</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.logo-setting.index') }}" class="nav-link"><i class="nav-icon fas fa-image"></i><p>Pengaturan Logo</p></a></li>
    @endsection
@endif

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ $isEdit ? route($panel . '.payment-methods.update', $method->id) : route($panel . '.payment-methods.create') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="form-group mb-3">
                    <label>Nama Metode</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $method->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-3">
                    <label>Tipe</label>
                    <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                        @foreach(['bank' => 'Bank', 'ewallet' => 'E-Wallet', 'qris' => 'QRIS', 'other' => 'Lainnya'] as $value => $label)
                            <option value="{{ $value }}" {{ old('type', $method->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-3">
                    <label>Nomor Rekening / Akun</label>
                    <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number', $method->account_number) }}">
                    @error('account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-3">
                    <label>Atas Nama</label>
                    <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ old('account_name', $method->account_name) }}">
                    @error('account_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-3">
                    <label>QR Image (opsional)</label>
                    <input type="file" name="qr_image" class="form-control-file @error('qr_image') is-invalid @enderror" accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp">
                    @error('qr_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    @if($method->qr_image)
                        <div class="mt-2"><img src="{{ $method->qrImageUrl() }}" alt="QR" style="max-height:120px"></div>
                    @endif
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $method->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Aktif</label>
                </div>

                <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route($panel . '.payment-methods.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection

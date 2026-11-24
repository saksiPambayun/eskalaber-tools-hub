@extends('layouts.app')

@section('title', 'Pengaturan Denda')
@section('page-title', 'Pengaturan Denda')

@if($panel === 'toolsman')
    @section('sidebar')
        <li class="nav-item"><a href="/" class="nav-link" target="_blank"><i class="nav-icon fas fa-globe"></i><p>Landing Page</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.dashboard') }}" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.loans.index') }}" class="nav-link"><i class="nav-icon fas fa-hand-holding"></i><p>Loans</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.fines.index') }}" class="nav-link"><i class="nav-icon fas fa-money-bill-wave"></i><p>Fines</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.payment-methods.index') }}" class="nav-link"><i class="nav-icon fas fa-credit-card"></i><p>Metode Pembayaran</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.fine-setting.index') }}" class="nav-link active"><i class="nav-icon fas fa-gavel"></i><p>Pengaturan Denda</p></a></li>
        <li class="nav-item"><a href="{{ route('toolsman.logo-setting.index') }}" class="nav-link"><i class="nav-icon fas fa-image"></i><p>Pengaturan Logo</p></a></li>
    @endsection
@endif

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Tarif Denda Global</h3></div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route($panel . '.fine-setting.update') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Nominal Denda per Hari (Rp)</label>
                        <input type="number" name="rate_per_day" min="0" max="1000000" step="1"
                               class="form-control @error('rate_per_day') is-invalid @enderror"
                               value="{{ old('rate_per_day', $setting->rate_per_day) }}" required>
                        @error('rate_per_day')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Berlaku global untuk semua alat. Perubahan hanya memengaruhi denda baru (snapshot). Batas: Rp 0–1.000.000/hari.</small>
                    </div>
                    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Tarif</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Riwayat Perubahan Tarif</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Oleh</th>
                            <th>Lama</th>
                            <th>Baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $h)
                            <tr>
                                <td>{{ $h->created_at?->format('d M Y H:i') }}</td>
                                <td>{{ $h->changedBy->name ?? '-' }}</td>
                                <td>Rp {{ number_format($h->old_rate, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($h->new_rate, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Belum ada perubahan tarif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

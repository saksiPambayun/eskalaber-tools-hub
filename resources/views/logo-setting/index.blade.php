@extends('layouts.app')

@section('title', 'Pengaturan Logo')
@section('page-title', 'Pengaturan Logo')

@if($panel === 'toolsman')
    @section('sidebar')
        <li class="nav-item">
            <a href="/" class="nav-link" target="_blank">
                <i class="nav-icon fas fa-globe"></i>
                <p>Landing Page</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('toolsman.dashboard') }}" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('toolsman.loans.index') }}" class="nav-link">
                <i class="nav-icon fas fa-hand-holding"></i>
                <p>Loans</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('toolsman.fines.index') }}" class="nav-link">
                <i class="nav-icon fas fa-money-bill-wave"></i>
                <p>Fines</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('toolsman.logo-setting.index') }}" class="nav-link active">
                <i class="nav-icon fas fa-image"></i>
                <p>Pengaturan Logo</p>
            </a>
        </li>
    @endsection
@endif

@section('content')
    <style>
        .logo-preview-box {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 190px;
            padding: 20px;
            background: #fff8f0;
            border: 2px dashed #f48c06;
            border-radius: 16px;
        }

        .logo-preview-box::before {
            content: '';
            position: absolute;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            border: 3px dashed #e85d04;
            opacity: .3;
            animation: logoPreviewSpin 10s linear infinite;
        }

        .logo-preview-box img {
            position: relative;
            z-index: 2;
            max-width: 90px;
            max-height: 90px;
            object-fit: contain;
            animation: logoPreviewFloat 3s infinite ease-in-out;
        }

        @keyframes logoPreviewSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes logoPreviewFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
    </style>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Logo Animasi Halaman Detail Katalog</h3>
                </div>

                <form action="{{ route($panel . '.logo-setting.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <p class="text-muted">
                            Perubahan ini berlaku global untuk semua halaman <code>/catalog/detail/{id}</code>.
                        </p>

                        <div class="form-group">
                            <label for="title">Judul Logo</label>
                            <input type="text" id="title" name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $setting->titleText()) }}"
                                maxlength="100" required>
                            @error('title')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="subtitle">Subteks</label>
                            <input type="text" id="subtitle" name="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                value="{{ old('subtitle', $setting->subtitleText()) }}"
                                maxlength="100" required>
                            @error('subtitle')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">File Logo</label>
                            <input type="file" id="image" name="image"
                                class="form-control-file @error('image') is-invalid @enderror"
                                accept=".png,.jpg,.jpeg,.webp,.gif,image/png,image/jpeg,image/webp,image/gif">
                            @error('image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Format: PNG, JPG, WEBP, atau GIF. Maksimal 2 MB. Kosongkan jika tidak ingin mengganti gambar.
                            </small>
                        </div>

                        <div class="form-group mb-0">
                            <label>Preview</label>
                            <div class="logo-preview-box">
                                <img src="{{ $setting->imageUrl() }}" alt="{{ $setting->titleText() }}">
                                <strong class="mt-2">{{ $setting->titleText() }}</strong>
                                <small class="text-muted">{{ $setting->subtitleText() }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

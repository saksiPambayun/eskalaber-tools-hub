@extends('layouts.app')

@section('title', 'Pengaturan Logo')
@section('page-title', 'Pengaturan Logo')

@section('content')
    <style>
        /* Styling Preview Logo */
        .logo-preview-box {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 250px;
            padding: 30px;
            background: linear-gradient(135deg, #FFF8F0, #FFF3E8);
            border: 2px dashed #F48C06;
            border-radius: 16px;
            box-shadow: inset 0 0 20px rgba(232, 93, 4, 0.05);
        }

        .logo-preview-box::before {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 3px dashed #E85D04;
            opacity: .2;
            animation: logoPreviewSpin 10s linear infinite;
        }

        .logo-preview-box img {
            position: relative;
            z-index: 2;
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
            animation: logoPreviewFloat 3s infinite ease-in-out;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        @keyframes logoPreviewSpin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes logoPreviewFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        /* Styling Form Biar Lebih Smooth */
        .form-group label {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            height: auto;
        }

        .form-control:focus {
            border-color: #E85D04;
            box-shadow: 0 0 0 0.2rem rgba(232, 93, 4, 0.15);
        }
    </style>

    <div class="row">
        <!-- KOLOM KIRI: FORM INPUT -->
        <div class="col-lg-7">
            <div class="card" style="border-radius: 15px; box-shadow: 0 5px 20px rgba(232, 93, 4, 0.08); border: none;">
                <div class="card-header" style="background: transparent; border-bottom: 2px solid #FFF3E8;">
                    <h3 class="card-title mb-0" style="font-weight: 700;">
                        <i class="fas fa-edit mr-2" style="color: #E85D04;"></i> Form Pengaturan
                    </h3>
                </div>

                <form action="{{ route($panel . '.logo-setting.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="form-group mb-4">
                            <label for="title">Judul Logo</label>
                            <input type="text" id="title" name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $setting->titleText()) }}" maxlength="100" required
                                placeholder="Masukkan judul logo...">
                            @error('title')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="subtitle">Subteks</label>
                            <input type="text" id="subtitle" name="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                value="{{ old('subtitle', $setting->subtitleText()) }}" maxlength="100" required
                                placeholder="Masukkan subteks...">
                            @error('subtitle')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label for="image">File Logo Baru</label>
                            <div class="custom-file">
                                <input type="file" id="image" name="image"
                                    class="custom-file-input @error('image') is-invalid @enderror"
                                    accept=".png,.jpg,.jpeg,.webp,.gif,image/png,image/jpeg,image/webp,image/gif">
                                <label class="custom-file-label" for="image" style="border-radius: 8px;">Pilih file
                                    gambar...</label>
                            </div>
                            @error('image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle"></i> Format: PNG, JPG, WEBP, atau GIF. Maksimal 2 MB. Biarkan
                                kosong jika tidak ingin mengganti gambar.
                            </small>
                        </div>
                    </div>

                    <div class="card-footer" style="background: transparent; border-top: 2px solid #FFF3E8;">
                        <button type="submit" class="btn btn-primary"
                            style="padding: 10px 30px; border-radius: 8px; font-weight: 700;">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: LIVE PREVIEW -->
        <div class="col-lg-5">
            <div class="card"
                style="border-radius: 15px; box-shadow: 0 5px 20px rgba(232, 93, 4, 0.08); border: none; height: 100%;">
                <div class="card-header" style="background: transparent; border-bottom: 2px solid #FFF3E8;">
                    <h3 class="card-title mb-0" style="font-weight: 700;">
                        <i class="fas fa-eye mr-2" style="color: #E85D04;"></i> Live Preview
                    </h3>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center" style="padding: 30px;">
                    <div class="logo-preview-box w-100">
                        <img src="{{ $setting->imageUrl() }}" alt="{{ $setting->titleText() }}">
                        <h5 class="mt-3 mb-1 font-weight-bold" style="color: #1a1a2e; position: relative; z-index: 2;">
                            {{ $setting->titleText() }}
                        </h5>
                        <small class="text-muted"
                            style="position: relative; z-index: 2; font-size: 14px;">{{ $setting->subtitleText() }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Script biar nama file yang dipilih muncul di input
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var fileName = document.getElementById("image").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
@endpush
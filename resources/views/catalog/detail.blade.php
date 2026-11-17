<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tool->name }} - Eskalaber Tools Hub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #E85D04;
            --primary-dark: #DC2F02;
            --primary-light: #F48C06;
            --primary-bg: #FFF3E8;
            --dark: #1a1a2e;
            --gray: #6c757d;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #FFF8F0;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* --- NAVBAR --- */
        .navbar-catalog {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 30px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            transition: background 0.3s ease;
        }

        .navbar-catalog .navbar-brand {
            font-weight: 800;
            font-size: 24px;
            color: var(--dark);
        }

        .navbar-catalog .navbar-brand span {
            color: var(--primary);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--primary);
            color: #fff;
        }

        /* --- TOOL DETAIL --- */
        .tool-detail {
            padding: 40px 0;
        }

        /* KIRI: GAMBAR */
        .tool-detail-image {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(232, 93, 4, 0.1);
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1 / 1;
            /* Bikin kotaknya persegi sempurna */
            max-height: 400px;
        }

        .tool-detail-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .tool-detail-image .placeholder {
            font-size: 100px;
            color: var(--primary);
            opacity: 0.3;
        }

        /* KIRI: KOTAK LOGO SARPRAS */
        .logo-sarpras-container {
            background: #fff;
            border-radius: 20px;
            padding: 25px 20px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            margin-top: 20px;
            border: 2px dashed var(--primary-light);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .logo-sarpras-container::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 160px;
            height: 160px;
            margin-top: -80px;
            margin-left: -80px;
            border-radius: 50%;
            border: 3px dashed var(--primary);
            opacity: 0.3;
            animation: spinRing 10s linear infinite;
            pointer-events: none;
        }

        .logo-sarpras-container::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            height: 200px;
            margin-top: -100px;
            margin-left: -100px;
            border-radius: 50%;
            border: 2px dotted var(--primary-dark);
            opacity: 0.15;
            animation: spinRingReverse 15s linear infinite;
            pointer-events: none;
        }

        .logo-sarpras-container img {
            max-width: 90px;
            height: auto;
            position: relative;
            z-index: 2;
            animation: floatPulse 3s infinite ease-in-out;
            margin: 5px 0;
        }

        @keyframes spinRing {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes spinRingReverse {
            0% {
                transform: rotate(360deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }

        @keyframes floatPulse {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0);
            }
        }

        /* KIRI: GRID MINI DASHBOARD (LOKASI & STOK) */
        .mini-dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .mini-card {
            background: #fff;
            border-radius: 16px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .mini-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary-light);
        }

        .mini-card .bg-icon {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 60px;
            color: var(--primary);
            opacity: 0.05;
            transform: rotate(-15deg);
        }

        .mini-card h6 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray);
            margin-bottom: 5px;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }

        .mini-card h4 {
            font-size: 16px;
            font-weight: 800;
            color: var(--dark);
            margin: 0;
            position: relative;
            z-index: 2;
        }

        /* KANAN: HEADER (JUDUL & STATUS) */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .title-area h1 {
            font-size: 38px;
            font-weight: 900;
            color: var(--dark);
            margin: 10px 0 5px;
            letter-spacing: -1px;
            transition: color 0.3s ease;
        }

        .tool-category-badge {
            display: inline-block;
            padding: 5px 14px;
            background: var(--primary-bg);
            color: var(--primary);
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-area {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #28a745;
            text-transform: uppercase;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            box-shadow: 0 0 10px #28a745;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        .stock-badge {
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 14px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .stock-badge.available {
            background: #d4edda;
            color: #155724;
        }

        .stock-badge.low {
            background: #fff3cd;
            color: #856404;
        }

        .stock-badge.out {
            background: #f8d7da;
            color: #721c24;
        }

        .tool-description {
            font-size: 15px;
            color: var(--gray);
            line-height: 1.6;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px dashed #d0d0d0;
        }

        /* KANAN: SPESIFIKASI */
        .tool-specs {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            transition: background 0.3s ease;
            margin-bottom: 25px;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .spec-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .spec-item:first-child {
            padding-top: 0;
        }

        .spec-label {
            color: var(--gray);
            font-weight: 600;
            font-size: 14px;
        }

        .spec-value {
            color: var(--dark);
            font-weight: 800;
            font-size: 14px;
        }

        /* KANAN: TOMBOL & INFO BAWAH */
        .btn-login-to-borrow {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 15px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 15px;
        }

        .btn-login-to-borrow:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(232, 93, 4, 0.3);
        }

        .info-card-animated {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 16px;
            padding: 15px 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-bg);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 15px;
        }

        .info-text {
            flex-grow: 1;
        }

        .pulse-dot {
            width: 10px;
            height: 10px;
            background: #28a745;
            border-radius: 50%;
            position: relative;
        }

        .pulse-dot::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #28a745;
            border-radius: 50%;
            animation: pulseGreen 1.5s infinite;
        }

        @keyframes pulseGreen {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(2.5);
                opacity: 0;
            }
        }

        /* --- DARK MODE --- */
        body.dark-mode {
            background-color: #0f0f1a !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .navbar-catalog {
            background-color: rgba(15, 15, 26, 0.95) !important;
        }

        body.dark-mode .navbar-catalog .navbar-brand {
            color: #fff !important;
        }

        body.dark-mode .tool-detail-image,
        body.dark-mode .tool-specs,
        body.dark-mode .logo-sarpras-container,
        body.dark-mode .info-card-animated,
        body.dark-mode .mini-card {
            background-color: #1a1a2e !important;
            border-color: #2d2d44 !important;
        }

        body.dark-mode .title-area h1,
        body.dark-mode .spec-value,
        body.dark-mode .mini-card h4 {
            color: #fff !important;
        }

        body.dark-mode .spec-item {
            border-bottom-color: #2d2d44 !important;
        }

        body.dark-mode .tool-description {
            border-bottom-color: #2d2d44 !important;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .tool-info {
                padding: 30px 0 0 0;
            }

            .header-top {
                flex-direction: column;
            }

            .status-area {
                text-align: left;
                align-items: flex-start;
                margin-top: 15px;
            }

            .tool-detail-image {
                aspect-ratio: auto;
                height: 300px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-catalog">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-tools me-2" style="color: var(--primary);"></i>
                Eskalaber<span>Tools</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('catalog') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                </a>
            </div>
        </div>
    </nav>

    <!-- TOOL DETAIL -->
    <section class="tool-detail">
        <div class="container">
            <div class="row g-4">

                <!-- KOLOM KIRI -->
                <div class="col-lg-5">
                    <!-- 1. Gambar Utama -->
                    <div class="tool-detail-image">
                        @if($tool->image)
                            <img src="{{ asset($tool->image) }}" alt="{{ $tool->name }}">
                        @else
                            <i class="fas fa-tools placeholder"></i>
                        @endif
                    </div>

                    <!-- 2. Animasi Logo -->
                    <div class="logo-sarpras-container">
                        <h6 class="fw-bold mb-1" style="font-size: 14px;">Sarana & Prasarana</h6>
                        <img src="{{ asset('images/download.jpeg') }}" alt="Logo Sarpras">
                        <p class="text-muted mt-1 mb-0" style="font-size: 12px; font-weight: 600;">Eskalaber Tools Hub
                        </p>
                    </div>

                    <!-- 3. Mini Dashboard (Lokasi & Stok) -->
                    <div class="mini-dashboard-grid">
                        <div class="mini-card">
                            <i class="fas fa-map-marker-alt bg-icon"></i>
                            <h6>Lokasi</h6>
                            <h4>{{ $tool->place->name ?? '-' }}</h4>
                        </div>
                        <div class="mini-card">
                            <i class="fas fa-cubes bg-icon"></i>
                            <h6>Total Stok</h6>
                            <h4>{{ $tool->stock }} Unit</h4>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN -->
                <div class="col-lg-7">
                    <div class="tool-info d-flex flex-column h-100">
                        <div>
                            <!-- Header (Judul & Status) -->
                            <div class="header-top">
                                <div class="title-area">
                                    <span class="tool-category-badge">
                                        <i class="fas fa-folder me-1"></i> {{ $tool->category->name ?? 'Umum' }}
                                    </span>
                                    <h1>{{ $tool->name }}</h1>
                                    <p class="text-muted mb-0" style="font-size: 14px;">Kode Aset: <strong
                                            style="color: var(--primary);">{{ $tool->code }}</strong></p>
                                </div>

                                <div class="status-area">
                                    <div class="live-indicator">
                                        <span class="live-dot"></span> Live
                                    </div>
                                    @if($tool->stock > 5)
                                        <span class="stock-badge available">
                                            <i class="fas fa-check-circle me-1"></i> Tersedia ({{ $tool->stock }})
                                        </span>
                                    @elseif($tool->stock > 0)
                                        <span class="stock-badge low">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Stok Terbatas
                                            ({{ $tool->stock }})
                                        </span>
                                    @else
                                        <span class="stock-badge out">
                                            <i class="fas fa-times-circle me-1"></i> Habis
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <p class="tool-description">
                                {{ $tool->description ?? 'Tidak ada deskripsi untuk alat ini.' }}
                            </p>

                            <!-- Spesifikasi -->
                            <div class="tool-specs">
                                <h6 class="fw-bold mb-3" style="font-size: 15px;">Detail Spesifikasi</h6>
                                <div class="spec-item">
                                    <span class="spec-label">Kategori</span>
                                    <span class="spec-value">{{ $tool->category->name ?? '-' }}</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Tipe</span>
                                    <span class="spec-value">{{ $tool->type->name ?? '-' }}</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Lokasi</span>
                                    <span class="spec-value">{{ $tool->place->name ?? '-' }}</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Stok Tersedia</span>
                                    <span class="spec-value">{{ $tool->stock }} unit</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Status</span>
                                    <span class="spec-value">
                                        @if($tool->stock > 0)
                                            <span class="text-success">Tersedia</span>
                                        @else
                                            <span class="text-danger">Tidak Tersedia</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol & Info Bawah -->
                        <div class="mt-auto">
                            @auth
                                @if(auth()->user()->role === 'USER')
                                    <a href="{{ route('user.loans.add', ['tool_id' => $tool->id]) }}"
                                        class="btn-login-to-borrow">
                                        <i class="fas fa-hand-holding me-2"></i> Pinjam Alat Sekarang
                                    </a>
                                @else
                                    <div class="alert alert-warning py-2" style="border-radius: 10px; font-size: 13px;">
                                        <i class="fas fa-info-circle"></i> Anda login sebagai
                                        <strong>{{ auth()->user()->role }}</strong>.
                                    </div>
                                    <a href="{{ route('admin.dashboard') }}" class="btn-login-to-borrow"
                                        style="background: #6c757d;">
                                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-login-to-borrow">
                                    <i class="fas fa-sign-in-alt me-2"></i> Login untuk Meminjam
                                </a>
                            @endauth

                            <div class="info-card-animated">
                                <div class="info-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="info-text">
                                    <h6 class="mb-1 fw-bold" style="font-size: 14px;">Siap Digunakan</h6>
                                    <small class="text-muted" style="font-size: 12px;">Alat ini terawat dan siap untuk
                                        dipinjam.</small>
                                </div>
                                <div class="pulse-dot"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- RELATED TOOLS -->
    @if($relatedTools->count() > 0)
        <section class="related-tools">
            <div class="container">
                <h3 class="mb-4" style="font-weight: 800;">Alat Serupa</h3>
                <div class="row g-4">
                    @foreach($relatedTools as $related)
                        <div class="col-md-6 col-lg-3">
                            <div class="tool-card"
                                style="background: var(--primary-bg); border-radius: 16px; overflow: hidden; transition: all 0.3s ease; height: 100%;">
                                <div
                                    style="height: 150px; display: flex; align-items: center; justify-content: center; background: #fff;">
                                    @if($related->image)
                                        <img src="{{ asset($related->image) }}" alt="{{ $related->name }}"
                                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    @else
                                        <i class="fas fa-tools" style="font-size: 50px; color: var(--primary); opacity: 0.3;"></i>
                                    @endif
                                </div>
                                <div style="padding: 15px;">
                                    <h6 style="font-weight: 700; color: var(--dark);">{{ $related->name }}</h6>
                                    <p class="text-muted mb-2" style="font-size: 13px;">Stok: {{ $related->stock }}</p>
                                    <a href="{{ route('catalog.detail', $related->id) }}"
                                        style="color: var(--primary); font-weight: 600; font-size: 13px; text-decoration: none;">
                                        Lihat Detail <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- FOOTER -->
    <footer class="footer-catalog"
        style="background: var(--dark); padding: 30px 0; margin-top: 50px; color: rgba(255, 255, 255, 0.7);">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Eskalaber Tools Hub. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT DARK MODE -->
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'true');
            } else {
                localStorage.setItem('darkMode', 'false');
            }
        }
    </script>
</body>

</html>
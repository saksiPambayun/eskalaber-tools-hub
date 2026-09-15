<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Eskalaber Tools Hub - Management System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
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
            --light-gray: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 30px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        .navbar-custom .navbar-brand {
            font-weight: 800;
            font-size: 24px;
            color: var(--dark);
        }

        .navbar-custom .navbar-brand span {
            color: var(--primary);
        }

        .navbar-custom .nav-link {
            font-weight: 500;
            color: var(--dark);
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: var(--primary);
        }

        .btn-orange {
            background: var(--primary);
            color: #fff;
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-orange:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(232, 93, 4, 0.3);
        }

        .btn-outline-orange {
            background: transparent;
            color: var(--primary);
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 600;
            border: 2px solid var(--primary);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-outline-orange:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(232, 93, 4, 0.3);
        }

        /* ===== HERO ===== */
        .hero-section {
            padding: 120px 0 80px;
            background: linear-gradient(135deg, #FFF8F0 0%, #FFE8D6 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(232, 93, 4, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(232, 93, 4, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(232, 93, 4, 0.1);
            color: var(--primary);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 56px;
            font-weight: 900;
            line-height: 1.1;
            color: var(--dark);
            margin-bottom: 20px;
        }

        .hero-title span {
            color: var(--primary);
        }

        .hero-subtitle {
            font-size: 18px;
            color: var(--gray);
            line-height: 1.8;
            max-width: 500px;
            margin-bottom: 30px;
        }

        .hero-image {
            position: relative;
            z-index: 2;
        }

        .hero-image img {
            max-width: 100%;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
        }

        /* ===== STATS ===== */
        .stats-section {
            padding: 60px 0;
            background: #fff;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 40px;
            font-weight: 800;
            color: var(--dark);
        }

        .stat-number span {
            color: var(--primary);
        }

        .stat-label {
            font-size: 16px;
            color: var(--gray);
            font-weight: 500;
        }

        /* ===== FEATURES ===== */
        .features-section {
            padding: 80px 0;
            background: var(--light-gray);
        }

        .section-title {
            font-size: 36px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .section-title span {
            color: var(--primary);
        }

        .section-subtitle {
            font-size: 18px;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto 50px;
        }

        .feature-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(232, 93, 4, 0.1);
            border-color: var(--primary-light);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: rgba(232, 93, 4, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
            color: var(--primary);
        }

        .feature-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .feature-desc {
            font-size: 15px;
            color: var(--gray);
            line-height: 1.6;
        }

        /* ===== WHY US ===== */
        .why-section {
            padding: 80px 0;
            background: #fff;
        }

        .why-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 25px;
        }

        .why-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
            background: rgba(232, 93, 4, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--primary);
        }

        .why-text h5 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .why-text p {
            color: var(--gray);
            font-size: 15px;
            margin: 0;
        }

        /* ===== TESTIMONIALS ===== */
        .testimonials-section {
            padding: 80px 0;
            background: var(--light-gray);
        }

        .testimonial-card {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            height: 100%;
            border: 1px solid rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            border-color: var(--primary-light);
            box-shadow: 0 10px 40px rgba(232, 93, 4, 0.08);
        }

        .testimonial-stars {
            color: #FFB800;
            margin-bottom: 15px;
        }

        .testimonial-text {
            font-size: 16px;
            color: var(--dark);
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: 18px;
        }

        .testimonial-name {
            font-weight: 600;
            color: var(--dark);
        }

        .testimonial-role {
            font-size: 14px;
            color: var(--gray);
        }

        /* ===== CTA ===== */
        .cta-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-title {
            font-size: 40px;
            font-weight: 800;
            color: #fff;
        }

        .cta-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.85);
            max-width: 500px;
            margin: 0 auto 30px;
        }

        .btn-cta {
            background: #fff;
            color: var(--primary);
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 700;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            color: var(--primary-dark);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--dark);
            padding: 50px 0 30px;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-brand {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
        }

        .footer-brand span {
            color: var(--primary);
        }

        .footer-text {
            font-size: 15px;
            line-height: 1.8;
            max-width: 300px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-social a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .footer-social a:hover {
            background: var(--primary);
            color: #fff;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 20px;
            margin-top: 30px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 36px;
            }

            .hero-section {
                padding: 100px 0 60px;
            }

            .section-title {
                font-size: 28px;
            }

            .cta-title {
                font-size: 30px;
            }

            .stat-number {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>

    <!-- ========================================== -->
    <!-- NAVBAR -->
    <!-- ========================================== -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-tools me-2" style="color: var(--primary);"></i>
                Eskalaber<span>Tools</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#why">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimoni</a></li>
                    <li class="nav-item ms-lg-2">
                        @auth
                            @php
                                $role = strtolower(auth()->user()->role);
                                // Mapping role ke route prefix yang benar
                                $routeMap = [
                                    'superadmin' => 'admin',
                                    'toolsman' => 'toolsman',
                                    'user' => 'user'
                                ];
                                $prefix = $routeMap[$role] ?? 'admin';
                                $dashboardRoute = $prefix . '.dashboard';
                            @endphp
                            <a href="{{ route($dashboardRoute) }}" class="btn-orange">
                                <i class="fas fa-th-large me-2"></i> Dashboard
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-outline-orange" style="border: none; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-orange">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ========================================== -->
    <!-- HERO -->
    <!-- ========================================== -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-rocket me-2"></i> Solusi Manajemen Alat Terbaik
                    </div>
                    <h1 class="hero-title">
                        Kelola Alat <br><span>Lebih Efisien</span>
                    </h1>
                    <p class="hero-subtitle">
                        Sistem manajemen alat yang memudahkan Anda mengelola inventaris,
                        peminjaman, dan perawatan alat secara profesional.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="btn-orange">
                            <i class="fas fa-rocket me-2"></i> Mulai Sekarang
                        </a>
                        <a href="#features" class="btn-outline-orange">
                            <i class="fas fa-play-circle me-2"></i> Lihat Fitur
                        </a>
                    </div>
                    <div class="mt-4 d-flex gap-4">
                        <div>
                            <span class="fw-bold text-dark fs-4">100+</span>
                            <span class="text-muted d-block">Alat Terdaftar</span>
                        </div>
                        <div>
                            <span class="fw-bold text-dark fs-4">50+</span>
                            <span class="text-muted d-block">User Aktif</span>
                        </div>
                        <div>
                            <span class="fw-bold text-dark fs-4">95%</span>
                            <span class="text-muted d-block">Kepuasan</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 hero-image text-center">
                    <img src="{{ asset('images/hero-tools.svg') }}" alt="Tools Management"
                        onerror="this.src='https://placehold.co/600x400/FFF3E8/E85D04?text=Eskalaber+Tools'">
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- STATS -->
    <!-- ========================================== -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-lg-3 stat-item">
                    <div class="stat-number"><span>100+</span></div>
                    <div class="stat-label">Total Alat</div>
                </div>
                <div class="col-6 col-lg-3 stat-item">
                    <div class="stat-number"><span>50+</span></div>
                    <div class="stat-label">User Aktif</div>
                </div>
                <div class="col-6 col-lg-3 stat-item">
                    <div class="stat-number"><span>300+</span></div>
                    <div class="stat-label">Transaksi</div>
                </div>
                <div class="col-6 col-lg-3 stat-item">
                    <div class="stat-number"><span>95%</span></div>
                    <div class="stat-label">Kepuasan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- FEATURES -->
    <!-- ========================================== -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Fitur <span>Unggulan</span></h2>
                <p class="section-subtitle">
                    Semua yang Anda butuhkan untuk mengelola alat dengan mudah dan profesional.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-qrcode"></i></div>
                        <h5 class="feature-title">QR Code Generator</h5>
                        <p class="feature-desc">Generate QR Code untuk setiap alat, memudahkan tracking dan
                            identifikasi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-hand-holding"></i></div>
                        <h5 class="feature-title">Manajemen Peminjaman</h5>
                        <p class="feature-desc">Kelola peminjaman alat dengan mudah, dari pengajuan hingga pengembalian.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-money-bill-wave"></i></div>
                        <h5 class="feature-title">Denda Otomatis</h5>
                        <p class="feature-desc">Sistem denda otomatis untuk peminjaman yang melewati batas waktu.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-chart-pie"></i></div>
                        <h5 class="feature-title">Dashboard & Laporan</h5>
                        <p class="feature-desc">Lihat statistik dan laporan lengkap tentang alat dan peminjaman.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-users-cog"></i></div>
                        <h5 class="feature-title">Multi-Role Access</h5>
                        <p class="feature-desc">Akses terpisah untuk Admin, Toolsman, dan User dengan hak akses berbeda.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-file-export"></i></div>
                        <h5 class="feature-title">Export Data</h5>
                        <p class="feature-desc">Export data peminjaman dan denda ke format CSV untuk analisis lebih
                            lanjut.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- WHY US -->
    <!-- ========================================== -->
    <section class="why-section" id="why">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title">Mengapa <span>Eskalaber Tools?</span></h2>
                    <p class="text-muted mb-4" style="font-size: 17px; line-height: 1.8;">
                        Kami hadir untuk membantu Anda mengelola alat dengan lebih efisien,
                        transparan, dan profesional.
                    </p>
                    <div class="why-item">
                        <div class="why-icon"><i class="fas fa-shield-alt"></i></div>
                        <div class="why-text">
                            <h5>Keamanan Data Terjamin</h5>
                            <p>Data Anda aman dengan sistem keamanan terbaik dan role-based access control.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-icon"><i class="fas fa-bolt"></i></div>
                        <div class="why-text">
                            <h5>Cepat & Efisien</h5>
                            <p>Proses peminjaman dan pengembalian alat menjadi lebih cepat dan terstruktur.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-icon"><i class="fas fa-headset"></i></div>
                        <div class="why-text">
                            <h5>Dukungan Tim Profesional</h5>
                            <p>Tim support siap membantu Anda dalam menggunakan platform ini.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('images/why-us.svg') }}" alt="Why Us" class="img-fluid"
                        onerror="this.src='https://placehold.co/500x400/FFF3E8/E85D04?text=Kenapa+Eskalaber+Tools%3F'">
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- TESTIMONIALS -->
    <!-- ========================================== -->
    <section class="testimonials-section" id="testimonials">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Apa Kata <span>Mereka?</span></h2>
                <p class="section-subtitle">Pengalaman nyata dari pengguna Eskalaber Tools.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">
                            "Eskalaber Tools sangat membantu kami dalam mengelola inventaris alat.
                            Proses peminjaman jadi lebih mudah dan terstruktur."
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar" style="background: var(--primary);">A</div>
                            <div>
                                <div class="testimonial-name">Ahmad F.</div>
                                <div class="testimonial-role">Manajer Operasional</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">
                            "Fitur QR Code sangat berguna untuk tracking alat.
                            Sekarang kami tahu persis posisi setiap alat."
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar" style="background: var(--primary-light);">S</div>
                            <div>
                                <div class="testimonial-name">Siti R.</div>
                                <div class="testimonial-role">Staff Administrasi</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">
                            "Sistem denda otomatis membuat pengembalian alat menjadi tepat waktu.
                            Sangat direkomendasikan!"
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar" style="background: #E85D04;">B</div>
                            <div>
                                <div class="testimonial-name">Budi P.</div>
                                <div class="testimonial-role">Kepala Gudang</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- CTA -->
    <!-- ========================================== -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content text-center">
                <h2 class="cta-title">Siap Mengelola Alat <br>dengan Lebih Mudah?</h2>
                <p class="cta-subtitle">
                    Bergabunglah dengan ribuan pengguna yang sudah merasakan kemudahan
                    mengelola alat dengan Eskalaber Tools.
                </p>
                <a href="{{ route('login') }}" class="btn-cta">
                    <i class="fas fa-rocket me-2"></i> Mulai Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- FOOTER -->
    <!-- ========================================== -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <i class="fas fa-tools me-2" style="color: var(--primary);"></i>
                        Eskalaber<span>Tools</span>
                    </div>
                    <p class="footer-text mt-3">
                        Sistem manajemen alat yang memudahkan Anda mengelola inventaris,
                        peminjaman, dan perawatan alat secara profesional.
                    </p>
                    <div class="footer-social mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="text-white fw-bold">Menu</h6>
                    <ul class="footer-links">
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#why">Tentang</a></li>
                        <li><a href="#testimonials">Testimoni</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="text-white fw-bold">Lainnya</h6>
                    <ul class="footer-links">
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Bantuan</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-white fw-bold">Kontak</h6>
                    <ul class="footer-links">
                        <li><i class="fas fa-envelope me-2" style="color: var(--primary);"></i> support@eskalaber.com
                        </li>
                        <li><i class="fas fa-phone me-2" style="color: var(--primary);"></i> +62 812 3456 7890</li>
                        <li><i class="fas fa-map-marker-alt me-2" style="color: var(--primary);"></i> Jakarta, Indonesia
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Eskalaber Tools Hub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- ========================================== -->
    <!-- SCRIPTS -->
    <!-- ========================================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>

</html>

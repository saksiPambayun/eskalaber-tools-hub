<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tool->name }} - Eskalaber Tools Hub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
        }

        .navbar-catalog {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 30px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
        }

        .navbar-catalog .navbar-brand {
            font-weight: 800;
            font-size: 24px;
            color: var(--dark);
        }

        .navbar-catalog .navbar-brand span { color: var(--primary); }

        .tool-detail {
            padding: 60px 0;
        }

        .tool-detail-image {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(232, 93, 4, 0.1);
        }

        .tool-detail-image img {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
        }

        .tool-detail-image .placeholder {
            font-size: 120px;
            color: var(--primary);
            opacity: 0.3;
        }

        .qr-section {
            margin-top: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(232, 93, 4, 0.1);
        }

        .qr-section img {
            max-width: 150px;
        }

        .tool-info {
            padding: 0 20px;
        }

        .tool-category-badge {
            display: inline-block;
            padding: 6px 16px;
            background: var(--primary-bg);
            color: var(--primary);
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .tool-info h1 {
            font-size: 36px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .tool-info .price-stock {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .stock-badge {
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
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
            font-size: 16px;
            color: var(--gray);
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .tool-specs {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .spec-item:last-child { border-bottom: none; }

        .spec-label {
            color: var(--gray);
            font-weight: 500;
        }

        .spec-value {
            color: var(--dark);
            font-weight: 600;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--primary);
            color: #fff;
        }

        .btn-login-to-borrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-login-to-borrow:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(232, 93, 4, 0.3);
        }

        .related-tools {
            padding: 60px 0;
            background: #fff;
        }

        .related-tools h3 {
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 30px;
        }

        .footer-catalog {
            background: var(--dark);
            padding: 40px 0 20px;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Dark Mode */
        body.dark-mode { background: #0f0f1a; color: #e0e0e0; }
        body.dark-mode .navbar-catalog { background: rgba(15, 15, 26, 0.95); }
        body.dark-mode .navbar-catalog .navbar-brand { color: #fff; }
        body.dark-mode .tool-detail-image { background: #1a1a2e; }
        body.dark-mode .qr-section { background: #1a1a2e; }
        body.dark-mode .tool-info h1 { color: #fff; }
        body.dark-mode .tool-specs { background: #1a1a2e; }
        body.dark-mode .spec-item { border-color: #2d2d44; }
        body.dark-mode .spec-value { color: #fff; }
        body.dark-mode .related-tools { background: #0f0f1a; }
        body.dark-mode .related-tools h3 { color: #fff; }
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
            <div class="row">
                <div class="col-lg-5">
                    <div class="tool-detail-image">
                        @if($tool->image)
                            <img src="{{ asset($tool->image) }}" alt="{{ $tool->name }}">
                        @else
                            <i class="fas fa-tools placeholder"></i>
                        @endif
                    </div>

                    @if($tool->qr_code)
                    <div class="qr-section">
                        <h6 class="fw-bold mb-3">QR Code</h6>
                        <img src="{{ asset($tool->qr_code) }}" alt="QR Code">
                        <p class="text-muted mt-2 mb-0" style="font-size: 13px;">Scan untuk detail alat</p>
                    </div>
                    @endif
                </div>

                <div class="col-lg-7">
                    <div class="tool-info">
                        <span class="tool-category-badge">
                            <i class="fas fa-folder me-1"></i> {{ $tool->category->name ?? 'Umum' }}
                        </span>
                        <h1>{{ $tool->name }}</h1>
                        <p class="text-muted mb-4">Kode: <strong>{{ $tool->code }}</strong></p>

                        <div class="price-stock">
                            @if($tool->stock > 5)
                                <span class="stock-badge available">
                                    <i class="fas fa-check-circle me-1"></i> Tersedia ({{ $tool->stock }})
                                </span>
                            @elseif($tool->stock > 0)
                                <span class="stock-badge low">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Stok Terbatas ({{ $tool->stock }})
                                </span>
                            @else
                                <span class="stock-badge out">
                                    <i class="fas fa-times-circle me-1"></i> Habis
                                </span>
                            @endif
                        </div>

                        <p class="tool-description">
                            {{ $tool->description ?? 'Tidak ada deskripsi untuk alat ini.' }}
                        </p>

                        <div class="tool-specs">
                            <h5 class="fw-bold mb-3">Spesifikasi</h5>
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

                        <div class="mt-4">
                            @auth
                                @if(auth()->user()->role === 'USER')
                                    <a href="{{ route('user.loans.add', ['tool_id' => $tool->id]) }}" class="btn-login-to-borrow">
                                        <i class="fas fa-hand-holding"></i> Pinjam Alat
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-login-to-borrow">
                                    <i class="fas fa-sign-in-alt"></i> Login untuk Meminjam
                                </a>
                            @endauth
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
            <h3>Alat Serupa</h3>
            <div class="row g-4">
                @foreach($relatedTools as $related)
                <div class="col-md-6 col-lg-3">
                    <div class="tool-card" style="background: var(--primary-bg); border-radius: 16px; overflow: hidden; transition: all 0.3s ease;">
                        <div style="height: 150px; display: flex; align-items: center; justify-content: center; background: #fff;">
                            @if($related->image)
                                <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            @else
                                <i class="fas fa-tools" style="font-size: 50px; color: var(--primary); opacity: 0.3;"></i>
                            @endif
                        </div>
                        <div style="padding: 15px;">
                            <h6 style="font-weight: 700; color: var(--dark);">{{ $related->name }}</h6>
                            <p class="text-muted mb-2" style="font-size: 13px;">Stok: {{ $related->stock }}</p>
                            <a href="{{ route('catalog.detail', $related->id) }}" style="color: var(--primary); font-weight: 600; font-size: 13px; text-decoration: none;">
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
    <footer class="footer-catalog">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Eskalaber Tools Hub. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

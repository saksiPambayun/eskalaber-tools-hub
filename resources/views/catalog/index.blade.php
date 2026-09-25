<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog Alat - Eskalaber Tools Hub</title>

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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #FFF8F0;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* ===== NAVBAR ===== */
        .navbar-catalog {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 30px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-catalog .navbar-brand {
            font-weight: 800;
            font-size: 24px;
            color: var(--dark);
        }

        .navbar-catalog .navbar-brand span {
            color: var(--primary);
        }

        .navbar-catalog .nav-link {
            font-weight: 500;
            color: var(--dark);
            padding: 10px 20px;
        }

        .navbar-catalog .nav-link:hover {
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
        }

        /* ===== HERO CATALOG ===== */
        .catalog-hero {
            padding: 80px 0 50px;
            background: linear-gradient(135deg, #FFF8F0 0%, #FFE8D6 100%);
            text-align: center;
        }

        .catalog-hero h1 {
            font-size: 48px;
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .catalog-hero h1 span {
            color: var(--primary);
        }

        .catalog-hero p {
            font-size: 18px;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto 30px;
        }

        /* ===== SEARCH BAR ===== */
        .search-container {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 18px 60px 18px 60px;
            border: 2px solid transparent;
            border-radius: 50px;
            font-size: 16px;
            background: #fff;
            box-shadow: 0 10px 40px rgba(232, 93, 4, 0.1);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 10px 40px rgba(232, 93, 4, 0.2);
        }

        .search-icon {
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 18px;
        }

        .search-clear {
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            cursor: pointer;
            display: none;
        }

        /* ===== FILTER CATEGORY ===== */
        .filter-section {
            padding: 30px 0;
        }

        .filter-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 20px;
        }

        .category-scroll {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 10px;
            scrollbar-width: thin;
        }

        .category-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .category-scroll::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        .category-btn {
            padding: 12px 24px;
            border-radius: 50px;
            background: #fff;
            border: 2px solid #f0f0f0;
            color: var(--dark);
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .category-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .category-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .category-btn .count {
            background: rgba(0, 0, 0, 0.1);
            padding: 2px 8px;
            border-radius: 50px;
            font-size: 12px;
        }

        .category-btn.active .count {
            background: rgba(255, 255, 255, 0.3);
        }

        /* ===== TOOL CARD ===== */
        .tools-grid {
            padding: 30px 0 80px;
        }

        .tool-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .tool-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(232, 93, 4, 0.15);
            border-color: var(--primary-light);
        }

        .tool-image {
            height: 200px;
            background: var(--primary-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .tool-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .tool-card:hover .tool-image img {
            transform: scale(1.05);
        }

        .tool-image .placeholder {
            font-size: 60px;
            color: var(--primary);
            opacity: 0.3;
        }

        .tool-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            background: #fff;
            color: var(--primary);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .tool-badge.available {
            background: #28a745;
            color: #fff;
        }

        .tool-badge.low-stock {
            background: #ffc107;
            color: #000;
        }

        .tool-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .tool-category {
            font-size: 12px;
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .tool-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .tool-desc {
            font-size: 14px;
            color: var(--gray);
            line-height: 1.6;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .tool-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .tool-stock {
            font-size: 13px;
            color: var(--gray);
        }

        .tool-stock strong {
            color: var(--dark);
            font-size: 16px;
        }

        .btn-detail {
            padding: 8px 20px;
            background: var(--primary);
            color: #fff;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateX(3px);
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: var(--primary);
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--gray);
        }

        /* ===== FOOTER ===== */
        .footer-catalog {
            background: var(--dark);
            padding: 40px 0 20px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 50px;
        }

        /* ===== DARK MODE ===== */
        body.dark-mode {
            background: #0f0f1a;
            color: #e0e0e0;
        }

        body.dark-mode .navbar-catalog {
            background: rgba(15, 15, 26, 0.95);
        }

        body.dark-mode .navbar-catalog .navbar-brand {
            color: #fff;
        }

        body.dark-mode .navbar-catalog .nav-link {
            color: #e0e0e0;
        }

        body.dark-mode .catalog-hero {
            background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 100%);
        }

        body.dark-mode .catalog-hero h1 {
            color: #fff;
        }

        body.dark-mode .catalog-hero p {
            color: #b0b0b0;
        }

        body.dark-mode .search-input {
            background: #1a1a2e;
            color: #e0e0e0;
            border-color: #2d2d44;
        }

        body.dark-mode .category-btn {
            background: #1a1a2e;
            border-color: #2d2d44;
            color: #e0e0e0;
        }

        body.dark-mode .category-btn.active {
            background: var(--primary);
            color: #fff;
        }

        body.dark-mode .tool-card {
            background: #1a1a2e;
            border-color: #2d2d44;
        }

        body.dark-mode .tool-title {
            color: #fff;
        }

        body.dark-mode .tool-desc {
            color: #b0b0b0;
        }

        body.dark-mode .tool-footer {
            border-color: #2d2d44;
        }

        body.dark-mode .tool-stock strong {
            color: #fff;
        }

        body.dark-mode .footer-catalog {
            background: #0a0a15;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .catalog-hero h1 {
                font-size: 32px;
            }

            .search-input {
                padding: 15px 50px;
                font-size: 14px;
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
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('catalog') }}">Katalog</a></li>
                    <li class="nav-item">
                        <button class="btn btn-link nav-link" id="darkModeToggle" title="Toggle Dark Mode">
                            <i class="fas fa-moon" id="darkModeIcon"></i>
                        </button>
                    </li>
                    @auth
                        @php
                            $role = strtolower(auth()->user()->role);
                            $routeMap = ['superadmin' => 'admin', 'toolsman' => 'toolsman', 'user' => 'user'];
                            $prefix = $routeMap[$role] ?? 'admin';
                            $dashboardRoute = $prefix . '.dashboard';
                        @endphp
                        <li class="nav-item ms-lg-2">
                            <a href="{{ route($dashboardRoute) }}" class="btn-orange">
                                <i class="fas fa-th-large me-2"></i> Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a href="{{ route('login') }}" class="btn-orange">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="catalog-hero">
        <div class="container">
            <h1>Katalog <span>Alat</span></h1>
            <p>Jelajahi semua alat yang tersedia di Eskalaber Tools Hub. Temukan alat yang Anda butuhkan dengan mudah.
            </p>

            <!-- Search Bar -->
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput"
                    placeholder="Cari alat berdasarkan nama, kode, atau deskripsi...">
                <i class="fas fa-times search-clear" id="searchClear"></i>
            </div>
        </div>
    </section>

    <!-- FILTER CATEGORY -->
    <section class="filter-section">
        <div class="container">
            <h5 class="filter-title">Kategori</h5>
            <div class="category-scroll" id="categoryFilter">
                <a href="{{ route('catalog') }}" class="category-btn {{ !request('category') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Semua
                    <span class="count">{{ $tools->total() }}</span>
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('catalog', ['category' => $category->id]) }}"
                        class="category-btn {{ request('category') == $category->id ? 'active' : '' }}">
                        <i class="fas fa-folder"></i> {{ $category->name }}
                        <span class="count">{{ $category->tools_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- TOOLS GRID -->
    <section class="tools-grid">
        <div class="container">
            <div class="row g-4" id="toolsGrid">
                @forelse($tools as $tool)
                    <div class="col-md-6 col-lg-4">
                        <div class="tool-card">
                            <div class="tool-image">
                                @if($tool->image)
                                    <img src="{{ asset($tool->image) }}" alt="{{ $tool->name }}">
                                @else
                                    <i class="fas fa-tools placeholder"></i>
                                @endif
                                @if($tool->stock > 5)
                                    <span class="tool-badge available">Tersedia</span>
                                @elseif($tool->stock > 0)
                                    <span class="tool-badge low-stock">Stok Terbatas</span>
                                @else
                                    <span class="tool-badge" style="background: #dc3545; color: #fff;">Habis</span>
                                @endif
                            </div>
                            <div class="tool-body">
                                <div class="tool-category">{{ $tool->category->name ?? 'Umum' }}</div>
                                <h5 class="tool-title">{{ $tool->name }}</h5>
                                <p class="tool-desc">{{ Str::limit($tool->description ?? 'Tidak ada deskripsi', 80) }}</p>
                                <div class="tool-footer">
                                    <div class="tool-stock">
                                        Stok: <strong>{{ $tool->stock }}</strong>
                                    </div>
                                    <a href="{{ route('catalog.detail', $tool->id) }}" class="btn-detail">
                                        Detail <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h4>Tidak Ada Alat</h4>
                            <p>Belum ada alat yang tersedia saat ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $tools->appends(request()->query())->links() }}
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer-catalog">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div style="font-size: 20px; font-weight: 800; color: #fff;">
                        <i class="fas fa-tools me-2" style="color: var(--primary);"></i>
                        Eskalaber<span style="color: var(--primary);">Tools</span>
                    </div>
                    <p class="mt-2 mb-0">Sistem manajemen alat yang memudahkan Anda.</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <p class="mb-0">&copy; {{ date('Y') }} Eskalaber Tools Hub. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ============================================
        // DARK MODE
        // ============================================
        document.addEventListener('DOMContentLoaded', function () {
            const darkMode = localStorage.getItem('darkMode');
            const icon = document.getElementById('darkModeIcon');
            if (darkMode === 'true') {
                document.body.classList.add('dark-mode');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        });

        document.getElementById('darkModeToggle')?.addEventListener('click', function (e) {
            e.preventDefault();
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark);
            const icon = document.getElementById('darkModeIcon');
            if (isDark) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        });

        // ============================================
        // SEARCH REAL-TIME
        // ============================================
        const searchInput = document.getElementById('searchInput');
        const searchClear = document.getElementById('searchClear');
        const toolsGrid = document.getElementById('toolsGrid');

        searchInput?.addEventListener('input', function () {
            const query = this.value;

            if (query.length > 0) {
                searchClear.style.display = 'block';
            } else {
                searchClear.style.display = 'none';
            }

            if (query.length >= 2) {
                fetch(`{{ route('catalog.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderTools(data.data.data);
                        }
                    });
            } else if (query.length === 0) {
                location.reload();
            }
        });

        searchClear?.addEventListener('click', function () {
            searchInput.value = '';
            this.style.display = 'none';
            location.reload();
        });

        function renderTools(tools) {
            if (tools.length === 0) {
                toolsGrid.innerHTML = `
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <h4>Tidak Ditemukan</h4>
                            <p>Alat yang Anda cari tidak ditemukan.</p>
                        </div>
                    </div>
                `;
                return;
            }

            let html = '';
            tools.forEach(tool => {
                const image = tool.image ?
                    `<img src="/${tool.image}" alt="${tool.name}">` :
                    `<i class="fas fa-tools placeholder"></i>`;

                let badge = '';
                if (tool.stock > 5) {
                    badge = '<span class="tool-badge available">Tersedia</span>';
                } else if (tool.stock > 0) {
                    badge = '<span class="tool-badge low-stock">Stok Terbatas</span>';
                }

                html += `
                    <div class="col-md-6 col-lg-4">
                        <div class="tool-card">
                            <div class="tool-image">
                                ${image}
                                ${badge}
                            </div>
                            <div class="tool-body">
                                <div class="tool-category">${tool.category?.name || 'Umum'}</div>
                                <h5 class="tool-title">${tool.name}</h5>
                                <p class="tool-desc">${(tool.description || 'Tidak ada deskripsi').substring(0, 80)}</p>
                                <div class="tool-footer">
                                    <div class="tool-stock">Stok: <strong>${tool.stock}</strong></div>
                                    <a href="/catalog/detail/${tool.id}" class="btn-detail">
                                        Detail <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            toolsGrid.innerHTML = html;
        }
    </script>
</body>

</html>
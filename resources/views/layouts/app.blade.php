<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Eskalaber Tools Hub')</title>

    <!-- Bootstrap 4 (Konsisten dengan AdminLTE 3) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            --primary: #E85D04;
            --primary-dark: #DC2F02;
            --primary-light: #F48C06;
            --primary-bg: #FFF3E8;
            --dark: #1a1a2e;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .content-wrapper {
            background: #FFF8F0;
        }

        /* Navbar Orange */
        .main-header {
            background: linear-gradient(135deg, #E85D04, #DC2F02) !important;
        }

        /* Sidebar Orange */
        .main-sidebar {
            background: linear-gradient(180deg, #1a1a2e 0%, #2d2d44 100%) !important;
        }

        .brand-link {
            background: linear-gradient(135deg, #E85D04, #DC2F02) !important;
            border-bottom: none !important;
        }

        .brand-text {
            color: #fff !important;
            font-weight: 800;
        }

        /* Nav Link */
        .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #fff !important;
            background: rgba(232, 93, 4, 0.2) !important;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #E85D04, #DC2F02) !important;
            color: #fff !important;
        }

        .nav-header {
            color: rgba(232, 93, 4, 0.7) !important;
            font-weight: 700;
        }

        /* User Panel */
        .user-panel {
            border-bottom: 1px solid rgba(232, 93, 4, 0.2) !important;
        }

        .user-panel .info a {
            color: #fff !important;
            font-weight: 600;
        }

        /* Cards */
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 20px rgba(232, 93, 4, 0.08);
        }

        .card-header {
            background: transparent;
            border-bottom: 2px solid var(--primary-bg);
        }

        /* Small Box */
        .small-box {
            border-radius: 15px;
            overflow: hidden;
        }

        .small-box .inner h3 {
            color: #fff;
            font-weight: 800;
        }

        .small-box .inner p {
            color: rgba(255, 255, 255, 0.9);
        }

        .small-box .icon {
            color: rgba(255, 255, 255, 0.3);
        }

        .small-box-footer {
            background: rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        /* Dropdown */
        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(232, 93, 4, 0.15);
            border: none;
        }

        .dropdown-item:hover {
            background: var(--primary-bg);
            color: var(--primary);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #E85D04, #DC2F02);
            border: none;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #DC2F02, #B71C1C);
        }

        /* Table */
        .table thead th {
            background: var(--primary-bg);
            color: var(--dark);
            font-weight: 700;
            border: none;
        }

        .table tbody tr:hover {
            background: #FFF8F0;
        }

        /* Pagination */
        .pagination .page-item.active .page-link {
            background: #E85D04;
            border-color: #E85D04;
        }

        .pagination .page-link {
            color: #E85D04;
        }

        /* Footer */
        .main-footer {
            background: #fff;
            border-top: 2px solid var(--primary-bg);
        }

        /* ============================================ */
        /* DARK MODE */
        /* ============================================ */
        body.dark-mode {
            background: #0f0f1a;
            color: #e0e0e0;
        }

        body.dark-mode .content-wrapper {
            background: #0f0f1a !important;
        }

        body.dark-mode .card {
            background: #1a1a2e;
            color: #e0e0e0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .card-header {
            background: #1a1a2e;
            border-bottom: 2px solid #2d2d44;
        }

        body.dark-mode .card-title {
            color: #e0e0e0 !important;
        }

        body.dark-mode .table {
            color: #e0e0e0;
        }

        body.dark-mode .table thead th {
            background: #2d2d44;
            color: #E85D04;
            border-color: #3d3d5c;
        }

        body.dark-mode .table td,
        body.dark-mode .table th {
            border-color: #2d2d44;
        }

        body.dark-mode .table tbody tr:hover {
            background: #2d2d44;
        }

        body.dark-mode .main-footer {
            background: #1a1a2e;
            color: #888;
            border-top: 2px solid #2d2d44;
        }

        body.dark-mode .content-header h1 {
            color: #e0e0e0;
        }

        body.dark-mode .breadcrumb {
            background: transparent;
        }

        body.dark-mode .breadcrumb a {
            color: #E85D04;
        }

        body.dark-mode .dropdown-menu {
            background: #1a1a2e;
            border: 1px solid #2d2d44;
        }

        body.dark-mode .dropdown-item {
            color: #e0e0e0;
        }

        body.dark-mode .dropdown-item:hover {
            background: #2d2d44;
            color: #E85D04;
        }

        body.dark-mode .dropdown-header {
            color: #E85D04;
        }

        body.dark-mode .dropdown-divider {
            border-color: #2d2d44;
        }

        body.dark-mode .small-box {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .form-control {
            background: #2d2d44;
            border-color: #3d3d5c;
            color: #e0e0e0;
        }

        body.dark-mode .form-control:focus {
            background: #2d2d44;
            color: #e0e0e0;
            border-color: #E85D04;
            box-shadow: 0 0 0 0.2rem rgba(232, 93, 4, 0.25);
        }

        body.dark-mode .input-group-text {
            background: #2d2d44;
            border-color: #3d3d5c;
            color: #e0e0e0;
        }

        body.dark-mode .modal-content {
            background: #1a1a2e;
            color: #e0e0e0;
        }

        body.dark-mode .close {
            color: #e0e0e0;
        }

        body.dark-mode .pagination .page-link {
            background: #1a1a2e;
            border-color: #2d2d44;
            color: #E85D04;
        }

        body.dark-mode .pagination .page-item.active .page-link {
            background: #E85D04;
            border-color: #E85D04;
            color: #fff;
        }

        body.dark-mode .alert {
            background: #2d2d44;
            border-color: #3d3d5c;
            color: #e0e0e0;
        }

        body.dark-mode hr {
            border-color: #2d2d44;
        }

        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode h4,
        body.dark-mode h5,
        body.dark-mode h6 {
            color: #e0e0e0;
        }

        body.dark-mode .text-muted {
            color: #888 !important;
        }

        body.dark-mode .bg-white {
            background: #1a1a2e !important;
        }

        body.dark-mode .card-body p {
            color: #b0b0b0;
        }

        body.dark-mode .table-responsive {
            background: #1a1a2e;
        }

        /* Sidebar tetap gelap */
        body.dark-mode .main-sidebar {
            background: #0a0a15 !important;
        }

        /* Navbar tetap gradient orange */
        body.dark-mode .main-header {
            background: linear-gradient(135deg, #E85D04, #DC2F02) !important;
        }

        /* Toastr Dark Mode */
        body.dark-mode #toast-container>div {
            background: #1a1a2e;
            color: #e0e0e0;
        }
    </style>

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- ============================================ -->
        <!-- NAVBAR -->
        <!-- ============================================ -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <!-- Toggle Dark Mode -->
                <li class="nav-item">
                    <a class="nav-link" href="#" id="darkModeToggle" role="button" title="Toggle Dark Mode">
                        <i class="fas fa-moon" id="darkModeIcon"></i>
                    </a>
                </li>

                <!-- Notifikasi - HANYA 1 -->
                <li class="nav-item dropdown" id="notificationDropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button" id="notificationBell">
                        <i class="fas fa-bell"></i>
                        <span class="badge badge-danger" id="notificationCount" style="display: none;">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right"
                        style="width: 350px; max-height: 400px; overflow-y: auto;">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <h6 class="mb-0 font-weight-bold">Notifikasi</h6>
                            <a href="#" id="markAllRead" class="text-primary" style="font-size: 12px;">Tandai semua
                                dibaca</a>
                        </div>
                        <div id="notificationList">
                            <div class="text-center py-3 text-muted">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                    </div>
                </li>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                            @if(auth()->user()->photo)
                                <img src="{{ asset(auth()->user()->photo) }}" class="rounded-circle"
                                    style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #fff;">
                            @else
                                <i class="fas fa-user-circle fa-lg"></i>
                            @endif
                            <span class="ml-2">{{ auth()->user()->name }}</span>
                            <span class="badge badge-warning ml-2">{{ auth()->user()->role }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('profile.index') }}" class="dropdown-item">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                            <a href="{{ route('profile.change_password') }}" class="dropdown-item">
                                <i class="fas fa-key"></i> Change Password
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
        </nav>
        <!-- END NAVBAR -->

        <!-- ============================================ -->
        <!-- SIDEBAR -->
        <!-- ============================================ -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-light">Eskalaber Tools</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                        <small class="text-muted">{{ auth()->user()->role }}</small>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        @yield('sidebar')
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END SIDEBAR -->

        <!-- ============================================ -->
        <!-- CONTENT WRAPPER -->
        <!-- ============================================ -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- ALERT BIASA DIHAPUS - PAKAI TOASTR SAJA -->
                    @yield('content')
                </div>
            </section>
        </div>
        <!-- END CONTENT WRAPPER -->

        <!-- ============================================ -->
        <!-- FOOTER -->
        <!-- ============================================ -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0
            </div>
            <strong>&copy; {{ date('Y') }} Eskalaber Tools Hub.</strong> All rights reserved.
        </footer>
        <!-- END FOOTER -->

    </div>
    <!-- END WRAPPER -->

    <!-- ============================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================ -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // ============================================
        // TOASTR CONFIG
        // ============================================
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "preventDuplicates": true,
            "maxOpened": 2,
            "newestOnTop": true,
        };

        // Tampilkan notifikasi session SEKALI saja
        @if(session('success'))
            toastr.success('{{ session('success') }}');
            @php session()->forget('success'); @endphp
        @endif

        @if(session('error'))
            toastr.error('{{ session('error') }}');
            @php session()->forget('error'); @endphp
        @endif

        @if(session('warning'))
            toastr.warning('{{ session('warning') }}');
            @php session()->forget('warning'); @endphp
        @endif

        @if(session('info'))
            toastr.info('{{ session('info') }}');
            @php session()->forget('info'); @endphp
        @endif

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
        // NOTIFIKASI REAL-TIME
        // ============================================
        function loadNotifications() {
            fetch('{{ route("notifications.index") }}')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('notificationList');
                    if (data.length === 0) {
                        list.innerHTML = '<div class="text-center py-3 text-muted"><i class="fas fa-inbox"></i> Tidak ada notifikasi</div>';
                        return;
                    }

                    let html = '';
                    data.forEach(notif => {
                        const bgColor = notif.is_read ? '#fff' : '#FFF3E8';
                        const iconMap = {
                            'info': 'fa-info-circle text-info',
                            'success': 'fa-check-circle text-success',
                            'warning': 'fa-exclamation-triangle text-warning',
                            'danger': 'fa-times-circle text-danger'
                        };
                        const icon = iconMap[notif.type] || 'fa-bell text-primary';

                        html += `
                        <a href="${notif.url || '#'}" class="dropdown-item py-2"
                           style="background: ${bgColor}; border-bottom: 1px solid #f0f0f0; white-space: normal;"
                           onclick="markAsRead(${notif.id}, event)">
                            <div class="d-flex">
                                <div class="mr-2">
                                    <i class="fas ${icon}" style="font-size: 20px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong style="font-size: 13px;">${notif.title}</strong>
                                    <p class="mb-0 text-muted" style="font-size: 12px;">${notif.message}</p>
                                    <small class="text-muted" style="font-size: 11px;">${timeAgo(notif.created_at)}</small>
                                </div>
                            </div>
                        </a>
                    `;
                    });

                    list.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        }

        function checkUnreadCount() {
            fetch('{{ route("notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationCount');
                    if (data.count > 0) {
                        badge.textContent = data.count > 9 ? '9+' : data.count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function markAsRead(id, event) {
            event.preventDefault();
            fetch(`/notifications/mark-as-read/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        checkUnreadCount();
                    }
                });
        }

        document.getElementById('markAllRead')?.addEventListener('click', function (e) {
            e.preventDefault();
            fetch('{{ route("notifications.mark-all-as-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotifications();
                        checkUnreadCount();
                    }
                });
        });

        function timeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);

            if (diff < 60) return 'Baru saja';
            if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
            if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
            if (diff < 604800) return Math.floor(diff / 86400) + ' hari lalu';
            return date.toLocaleDateString('id-ID');
        }

        // Load notifikasi saat dropdown dibuka
        document.getElementById('notificationBell')?.addEventListener('click', function () {
            loadNotifications();
        });

        // Cek unread count setiap 30 detik (bukan 10 detik)
        checkUnreadCount();
        setInterval(checkUnreadCount, 30000);
    </script>

    @stack('scripts')
</body>

</html>
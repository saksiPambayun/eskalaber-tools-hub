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
        body {
            font-family: 'Inter', sans-serif;
        }

        .content-wrapper {
            background: #f4f6f9;
        }

        .main-header {
            background: #1a2332 !important;
        }

        .main-sidebar {
            background: #1a2332 !important;
        }

        .brand-link {
            background: #1a2332 !important;
            border-bottom: 1px solid #2c3e50 !important;
        }

        .brand-text {
            color: #fff !important;
            font-weight: 700;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }

        .nav-header {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .user-panel {
            border-bottom: 1px solid #2c3e50 !important;
        }

        .user-panel .info {
            color: #fff !important;
        }

        .user-panel .info a {
            color: #fff !important;
        }

        .small-box {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
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

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Konfigurasi Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
        };

        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif

        @if(session('error'))
            toastr.error('{{ session('error') }}');
        @endif

        @if(session('warning'))
            toastr.warning('{{ session('warning') }}');
        @endif

        @if(session('info'))
            toastr.info('{{ session('info') }}');
        @endif
    </script>

    @stack('scripts')
</body>

</html>
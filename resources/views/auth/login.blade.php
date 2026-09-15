<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Eskalaber Tools Hub</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #E85D04;
            --primary-dark: #DC2F02;
            --primary-light: #F48C06;
            --primary-bg: #FFF3E8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #FFF8F0 0%, #FFE8D6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: #fff;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(232, 93, 4, 0.15);
            max-width: 450px;
            width: 100%;
            position: relative;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 40px 30px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .login-header h2 {
            color: #fff;
            font-weight: 800;
            font-size: 28px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin: 5px 0 0;
            position: relative;
            z-index: 2;
        }

        .login-body {
            padding: 30px 30px 0;
            position: relative;
            z-index: 2;
        }

        .input-group-custom {
            background: var(--primary-bg);
            border-radius: 50px;
            padding: 5px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .input-group-custom:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(232, 93, 4, 0.1);
        }

        .input-group-custom .icon {
            color: var(--primary);
            font-size: 18px;
            width: 25px;
            text-align: center;
        }

        .input-group-custom input {
            border: none;
            background: transparent;
            padding: 12px 15px;
            width: 100%;
            font-size: 15px;
            outline: none;
            color: #333;
        }

        .input-group-custom input::placeholder {
            color: #aaa;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 14px;
            width: 100%;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            margin-top: 10px;
            cursor: pointer;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-dark), #B71C1C);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(232, 93, 4, 0.4);
        }

        .wave-container {
            position: relative;
            height: 120px;
            margin-top: 20px;
            overflow: hidden;
        }

        .wave-container svg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .login-footer {
            text-align: center;
            padding: 15px 30px 30px;
            position: relative;
            z-index: 2;
            background: #fff;
        }

        .login-footer p {
            color: #888;
            font-size: 14px;
            margin: 0;
        }

        .login-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 15px;
            font-size: 14px;
        }

        .default-account {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background: var(--primary-bg);
            border-radius: 15px;
            font-size: 13px;
            color: #666;
        }

        .default-account strong {
            color: var(--primary);
        }

        /* Wave Animation */
        .wave {
            animation: wave 3s ease-in-out infinite;
        }

        @keyframes wave {

            0%,
            100% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(-10px);
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <h2>Login</h2>
            <p>To Continue</p>
        </div>

        <!-- Body -->
        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="input-group-custom">
                    <i class="fas fa-envelope icon"></i>
                    <input type="email" name="email" placeholder="someone@gmail.com" value="{{ old('email') }}" required
                        autofocus>
                </div>

                <div class="input-group-custom">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" name="password" placeholder="••••••••••••" required>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>
            </form>

            <div class="default-account">
                <i class="fas fa-info-circle"></i>
                Default: <strong>admin@eskalaber.com</strong> / <strong>password</strong>
            </div>
        </div>

        <!-- Wave -->
        <div class="wave-container">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                <path d="M0,60 C150,120 350,0 500,60 L500,150 L0,150 Z" fill="rgba(232, 93, 4, 0.1)" class="wave">
                </path>
                <path d="M0,80 C150,140 350,20 500,80 L500,150 L0,150 Z" fill="rgba(232, 93, 4, 0.2)" class="wave"
                    style="animation-delay: 0.5s;"></path>
                <path d="M0,100 C150,160 350,40 500,100 L500,150 L0,150 Z" fill="rgba(232, 93, 4, 0.3)" class="wave"
                    style="animation-delay: 1s;"></path>
                <path d="M0,120 C150,170 350,60 500,120 L500,150 L0,150 Z" fill="#E85D04" class="wave"
                    style="animation-delay: 1.5s;"></path>
            </svg>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p>Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a></p>
            <p class="mt-2"><a href="/" style="color: #888; font-size: 13px;"><i class="fas fa-arrow-left me-1"></i>
                    Kembali ke Landing Page</a></p>
        </div>
    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $tool->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a2332 0%, #2c3e50 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .qr-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 50px 40px 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
        }

        .qr-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #1a2332, #3498db, #1a2332);
        }

        .qr-header {
            margin-bottom: 30px;
        }

        .qr-header .icon {
            font-size: 48px;
            color: #1a2332;
            background: rgba(26, 35, 50, 0.08);
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 15px;
        }

        .qr-header h2 {
            font-weight: 700;
            color: #1a2332;
            margin-bottom: 5px;
        }

        .qr-header .subtitle {
            color: #6c757d;
            font-size: 14px;
        }

        .qr-code-wrapper {
            background: #ffffff;
            border: 2px dashed #e9ecef;
            border-radius: 16px;
            padding: 30px;
            margin: 20px 0 25px;
            transition: all 0.3s ease;
        }

        .qr-code-wrapper:hover {
            border-color: #1a2332;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .qr-code-wrapper img {
            max-width: 250px;
            height: auto;
        }

        .tool-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .tool-info .label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .tool-info .value {
            font-size: 18px;
            font-weight: 600;
            color: #1a2332;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 5px;
        }

        .status-badge.available {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.borrowed {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.broken {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-group-custom {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-custom-primary {
            background: #1a2332;
            color: #fff;
            border: 2px solid #1a2332;
        }

        .btn-custom-primary:hover {
            background: #2c3e50;
            border-color: #2c3e50;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 35, 50, 0.3);
        }

        .btn-custom-secondary {
            background: transparent;
            color: #1a2332;
            border: 2px solid #1a2332;
        }

        .btn-custom-secondary:hover {
            background: #1a2332;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 35, 50, 0.15);
        }

        .btn-custom-success {
            background: #28a745;
            color: #fff;
            border: 2px solid #28a745;
        }

        .btn-custom-success:hover {
            background: #218838;
            border-color: #218838;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .divider {
            border: none;
            border-top: 1px solid #e9ecef;
            margin: 20px 0;
        }

        .footer-note {
            font-size: 12px;
            color: #adb5bd;
            margin-top: 15px;
        }

        .footer-note i {
            margin-right: 5px;
        }

        @media (max-width: 576px) {
            .qr-container {
                padding: 30px 20px 25px;
            }

            .qr-code-wrapper img {
                max-width: 180px;
            }

            .btn-group-custom {
                flex-direction: column;
            }

            .btn-custom {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="qr-container">
        <!-- Header -->
        <div class="qr-header">
            <div class="icon">
                <i class="fas fa-qrcode"></i>
            </div>
            <h2>QR Code</h2>
            <p class="subtitle">Scan untuk melihat detail alat</p>
        </div>

        <!-- QR Code -->
        <div class="qr-code-wrapper">
            @if($tool->qr_code)
                <img src="{{ asset($tool->qr_code) }}" alt="QR Code {{ $tool->name }}">
            @else
                <div class="text-muted">
                    <i class="fas fa-times-circle fa-3x"></i>
                    <p class="mt-2">QR Code belum tersedia</p>
                </div>
            @endif
        </div>

        <!-- Tool Info -->
        <div class="tool-info">
            <div class="label">Nama Alat</div>
            <div class="value">{{ $tool->name }}</div>
            <div style="font-size: 14px; color: #6c757d; margin-top: 2px;">
                Kode: {{ $tool->code }}
            </div>
            <span class="status-badge {{ $tool->status }}">
                {{ ucfirst($tool->status) }}
            </span>
        </div>

        <hr class="divider">

        <!-- Actions -->
        <div class="btn-group-custom">
            <a href="{{ route('admin.tools.generate-qr', $tool->id) }}" class="btn-custom btn-custom-success">
                <i class="fas fa-sync-alt"></i> Regenerate
            </a>
            <a href="{{ asset($tool->qr_code) }}" download class="btn-custom btn-custom-primary">
                <i class="fas fa-download"></i> Download
            </a>
            <a href="{{ route('admin.tools.index') }}" class="btn-custom btn-custom-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="footer-note">
            <i class="fas fa-info-circle"></i>
            QR Code untuk alat {{ $tool->name }}
        </div>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kata Sandi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Ubah Kata Sandi</h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('profile.change_password') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label>Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Kata Sandi Baru</label>
                                <input type="password" name="new_password" class="form-control" required minlength="8">
                            </div>
                            <div class="mb-3">
                                <label>Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Perbarui Kata Sandi</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
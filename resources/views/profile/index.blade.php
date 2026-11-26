@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('sidebar')
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dasbor</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('profile.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-user"></i>
            <p>Profil Saya</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('profile.change_password') }}" class="nav-link">
            <i class="nav-icon fas fa-key"></i>
            <p>Ubah Kata Sandi</p>
        </a>
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <!-- Photo Profil -->
                    <div class="position-relative d-inline-block">
                        @if(auth()->user()->photo)
                            <img src="{{ asset(auth()->user()->photo) }}" class="rounded-circle"
                                style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #1a2332;">
                        @else
                            <i class="fas fa-user-circle fa-8x text-primary" style="font-size: 150px;"></i>
                        @endif

                        <!-- Tombol Unggah -->
                        <button class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle"
                            style="width: 40px; height: 40px;" onclick="document.getElementById('photoInput').click()">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>

                    <!-- Form Unggah (Hidden) -->
                    <form id="photoForm" action="{{ route('profile.update_photo') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="photoInput" name="photo" accept="image/*" style="display: none;"
                            onchange="document.getElementById('photoForm').submit();">
                    </form>

                    <!-- Tombol Hapus Foto -->
                    @if(auth()->user()->photo)
                        <form action="{{ route('profile.delete_photo') }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin hapus foto profile?')">
                                <i class="fas fa-trash"></i> Hapus Foto
                            </button>
                        </form>
                    @endif

                    <h5 class="card-title mt-2">{{ auth()->user()->name }}</h5>
                    <p>
                        <span
                            class="badge bg-{{ auth()->user()->role == 'SUPERADMIN' ? 'danger' : (auth()->user()->role == 'TOOLSMAN' ? 'success' : 'warning') }}">
                            {{ auth()->user()->role }}
                        </span>
                    </p>
                    <div class="mt-3">
                        <a href="{{ route('profile.change_password') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-key"></i> Ubah Kata Sandi
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Informasi Profil</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>{{ auth()->user()->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ auth()->user()->email }}</td>
                        </tr>
                        <tr>
                            <th>Peran</th>
                            <td>{{ auth()->user()->role }}</td>
                        </tr>
                        <tr>
                            <th>Departemen</th>
                            <td>{{ auth()->user()->department->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ auth()->user()->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ auth()->user()->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bergabung Sejak</th>
                            <td>{{ auth()->user()->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto submit form ketika file dipilih
        document.getElementById('photoInput').addEventListener('change', function () {
            if (this.files && this.files[0]) {
                document.getElementById('photoForm').submit();
            }
        });
    </script>
@endpush
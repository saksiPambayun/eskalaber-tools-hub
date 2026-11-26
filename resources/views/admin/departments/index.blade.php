@extends('layouts.app')

@section('title', 'Departemen')
@section('page-title', 'Manajemen Departemen')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Departemen</h3>
            <div class="card-tools">
                <a href="{{ route('admin.departments.add') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Departemen
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Departemen</th>
                        <th>Dibuat Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $index => $dept)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dept->name }}</td>
                            <td>{{ $dept->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.departments.update', $dept->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.departments.delete', $dept->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

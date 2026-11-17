@extends('layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Categories</h3>
            <div class="card-tools">
                <a href="{{ route('admin.categories.add') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Category
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
                        <th>Nama Category</th>
                        <th>Created At</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.categories.update', $category->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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

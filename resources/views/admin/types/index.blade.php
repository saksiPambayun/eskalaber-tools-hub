@extends('layouts.app')

@section('title', 'Types')
@section('page-title', 'Types Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Types</h3>
            <div class="card-tools">
                <a href="{{ route('admin.types.add') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Type
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
                        <th>Nama Type</th>
                        <th>Created At</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($types as $index => $type)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $type->name }}</td>
                            <td>{{ $type->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.types.update', $type->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.types.delete', $type->id) }}" method="POST" class="d-inline"
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
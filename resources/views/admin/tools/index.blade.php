@extends('layouts.app')

@section('title', 'Alat')
@section('page-title', 'Manajemen Alat')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Alat</h3>
            <div class="card-tools">
                <!-- TOMBOL EXPORT CSV -->
                <a href="{{ route('admin.tools.export-csv') }}" class="btn btn-success btn-sm mr-2">
                    <i class="fas fa-file-excel"></i> Ekspor Excel
                </a>
                <!-- TOMBOL TAMBAH TOOL -->
                <a href="{{ route('admin.tools.add') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Alat
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tools as $index => $tool)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($tool->image)
                                        <img src="{{ asset($tool->image) }}" width="50" height="50"
                                            style="object-fit: cover; border-radius: 5px;">
                                    @else
                                        <i class="fas fa-tools fa-2x text-muted"></i>
                                    @endif
                                </td>
                                <td>{{ $tool->code }}</td>
                                <td>{{ $tool->name }}</td>
                                <td>{{ $tool->category->name ?? '-' }}</td>
                                <td>{{ $tool->stock }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $tool->status == 'available' ? 'success' : ($tool->status == 'borrowed' ? 'warning' : 'danger') }}">
                                        {{ match($tool->status) { 'available' => 'Tersedia', 'borrowed' => 'Dipinjam', 'broken' => 'Rusak', 'maintenance' => 'Perawatan', default => ucfirst($tool->status) } }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.tools.detail', $tool->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tools.update', $tool->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tools.delete', $tool->id) }}" method="POST" class="d-inline"
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
                                <td colspan="9" class="text-center">Belum ada data tool</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
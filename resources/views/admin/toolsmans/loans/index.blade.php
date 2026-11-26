@extends('layouts.app')

@section('title', 'Petugas Alat - Peminjaman')
@section('page-title', 'Manajemen Peminjaman')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Peminjaman</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>Alat</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ $item->tool->name ?? '-' }}</td>
                            <td>{{ $item->loan_date->format('d M Y') }}</td>
                            <td>{{ $item->return_date->format('d M Y') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $item->status == 'pending' ? 'warning' : ($item->status == 'approved' ? 'primary' : ($item->status == 'borrowed' ? 'info' : ($item->status == 'returned' ? 'success' : 'danger'))) }}">
                                    {{ match($item->status) { 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'borrowed' => 'Dipinjam', 'returned' => 'Dikembalikan', 'cancelled' => 'Dibatalkan', 'rejected' => 'Ditolak', default => ucfirst($item->status) } }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('toolsman.loans.detail', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

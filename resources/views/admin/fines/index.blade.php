@extends('layouts.app')

@section('title', 'Manajemen Pembayaran')
@section('page-title', 'Manajemen Pembayaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pembayaran</h3>
        <div class="card-tools">
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-file-excel"></i> Ekspor
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.fines.export-csv') }}" class="dropdown-item">Semua Data</a>
                    <a href="{{ route('admin.fines.export-csv') }}?status=unpaid" class="dropdown-item">Belum Lunas</a>
                    <a href="{{ route('admin.fines.export-csv') }}?status=pending_verification" class="dropdown-item">Menunggu Verifikasi</a>
                    <a href="{{ route('admin.fines.export-csv') }}?status=paid" class="dropdown-item">Lunas</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="mb-3">
            @foreach(['all' => 'Semua', 'unpaid' => 'Belum Lunas', 'pending_verification' => 'Menunggu', 'paid' => 'Lunas', 'rejected' => 'Ditolak'] as $key => $label)
                <a href="{{ route('admin.fines.index', ['status' => $key]) }}"
                   class="btn btn-sm {{ $status == $key ? 'btn-primary' : 'btn-outline-primary' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr><th>No</th><th>Pengguna</th><th>Pinjaman</th><th>Jumlah</th><th>Status</th><th>Bukti</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($fines as $index => $fine)
                        @php $badge = match($fine->status){'unpaid'=>'danger','pending_verification'=>'warning','paid'=>'success','rejected'=>'dark',default=>'secondary'}; @endphp
                        <tr>
                            <td>{{ $fines->firstItem() + $index }}</td>
                            <td>{{ $fine->user->name ?? '-' }}</td>
                            <td>#{{ $fine->loan_id }}</td>
                            <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $badge }}">{{ match($fine->status) { 'unpaid' => 'Belum Lunas', 'pending_verification' => 'Menunggu Verifikasi', 'paid' => 'Lunas', 'rejected' => 'Ditolak', default => str_replace('_',' ',ucfirst($fine->status)) } }}</span></td>
                            <td>@if($fine->proof_image)<a href="{{ $fine->proofImageUrl() }}" target="_blank">Lihat</a>@else - @endif</td>
                            <td>
                                <a href="{{ route('admin.fines.detail', $fine->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('admin.fines.delete', $fine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada data pembayaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $fines->appends(['status' => $status])->links() }}</div>
    </div>
</div>
@endsection

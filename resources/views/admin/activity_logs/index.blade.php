@extends('layouts.app')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Aktivitas</h3>
            <div class="card-tools">
                <span class="badge badge-info p-2">Total: {{ $logs->total() }} data</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>IP Alamat</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr>
                                <td>{{ $logs->firstItem() + $index }}</td>
                                <td>
                                    <i class="fas fa-user-circle text-muted mr-1"></i>
                                    {{ $log->user->name ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ match($log->action) { 'approve_loan' => 'Setujui Pinjaman', 'reject_loan' => 'Tolak Pinjaman', 'cancel_loan' => 'Batalkan Pinjaman', 'return_loan' => 'Kembalikan Alat', 'create_loan' => 'Ajukan Pinjaman', 'submit_fine_payment' => 'Unggah Pembayaran', 'verify_fine' => 'Verifikasi Pembayaran', 'pay_fine' => 'Tandai Lunas', 'update_fine_rate' => 'Ubah Tarif Pembayaran', 'UPDATE_LOGO_SETTING' => 'Ubah Pengaturan Logo', 'update_logo_setting' => 'Ubah Pengaturan Logo', default => str_replace('_', ' ', ucfirst($log->action)) } }}</span>
                                </td>
                                <td>{{ $log->description ?? '-' }}</td>
                                <td>
                                    <code class="text-dark">{{ $log->ip_address ?? '-' }}</code>
                                </td>
                                <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    Belum ada aktivitas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION YANG SUDAH DIRAPIKAN -->
        <div class="card-footer clearfix">
            <div class="float-left">
                <small class="text-muted">
                    Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }}
                    dari {{ $logs->total() }} data
                </small>
            </div>
            <div class="float-right">
                {{-- Paksa pagination pakai desain Bootstrap 4 bawaan AdminLTE --}}
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
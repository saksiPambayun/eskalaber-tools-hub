@extends('layouts.app')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Aktivitas</h3>
            <div class="card-tools">
                <span class="text-muted">Total: {{ $logs->total() }} data</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>IP Address</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr>
                                <td>{{ $logs->firstItem() + $index }}</td>
                                <td>{{ $log->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($log->action)) }}</span>
                                </td>
                                <td>{{ $log->description ?? '-' }}</td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                                <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada aktivitas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <span class="text-muted">
                        Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }}
                        dari {{ $logs->total() }} data
                    </span>
                </div>
                <div class="col-md-6 text-right">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

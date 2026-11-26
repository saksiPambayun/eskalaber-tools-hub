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
            <form method="GET" action="{{ route('activity_logs.index') }}" class="form-inline mb-3">
                <div class="form-group mr-2 mb-2">
                    <label for="from" class="mr-2">Dari</label>
                    <input type="date" name="from" id="from" class="form-control form-control-sm"
                        value="{{ $from }}">
                </div>
                <div class="form-group mr-2 mb-2">
                    <label for="to" class="mr-2">Sampai</label>
                    <input type="date" name="to" id="to" class="form-control form-control-sm"
                        value="{{ $to }}">
                </div>
                <button type="submit" class="btn btn-sm btn-primary mr-2 mb-2">
                    <i class="fas fa-search mr-1"></i> Terapkan
                </button>
                <a href="{{ route('activity_logs.index') }}" class="btn btn-sm btn-secondary mr-2 mb-2">
                    Atur Ulang
                </a>
                <a href="{{ route('activity_logs.export-csv', ['from' => $from, 'to' => $to]) }}"
                    class="btn btn-sm btn-success mb-2">
                    <i class="fas fa-file-csv mr-1"></i> Ekspor CSV
                </a>
            </form>

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
                                    <span class="badge badge-info">{{ $log->actionLabel() }}</span>
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

        <div class="card-footer clearfix">
            <div class="float-left">
                <small class="text-muted">
                    Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }}
                    dari {{ $logs->total() }} data
                </small>
            </div>
            <div class="float-right">
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection

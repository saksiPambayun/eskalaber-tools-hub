@extends('layouts.app')

@section('title', 'Detail Denda')
@section('page-title', 'Detail Denda')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail Denda #{{ $fine->id }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 220px;">User</th>
                            <td>{{ $fine->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Peminjaman</th>
                            <td>
                                @if($fine->loan)
                                    #{{ $fine->loan->id }} - {{ $fine->loan->tool->name ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Jumlah Denda</th>
                            <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Keterlambatan</th>
                            <td>{{ $fine->late_days ?? 0 }} hari</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $fine->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    class="badge bg-{{ $fine->status == 'paid' ? 'success' : ($fine->status == 'cancelled' ? 'secondary' : 'danger') }}">
                                    {{ ucfirst($fine->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Bayar</th>
                            <td>{{ $fine->payment_date ? $fine->payment_date->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>{{ $fine->payment_method ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Referensi Pembayaran</th>
                            <td>{{ $fine->payment_reference ?? '-' }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        @if($fine->status == 'unpaid')
                            <form action="{{ route('admin.fines.update-status', $fine->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="paid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i> Tandai Lunas
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

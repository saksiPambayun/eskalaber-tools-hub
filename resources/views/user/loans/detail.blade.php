@extends('layouts.app')

@section('title', 'Detail Loan')
@section('page-title', 'Detail Peminjaman')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail Peminjaman</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Tool</th>
                            <td>{{ $loan->tool->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <td>{{ $loan->loan_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kembali</th>
                            <td>{{ $loan->return_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    class="badge bg-{{ $loan->status == 'pending' ? 'warning' : ($loan->status == 'approved' ? 'primary' : ($loan->status == 'borrowed' ? 'info' : ($loan->status == 'returned' ? 'success' : 'danger'))) }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $loan->notes ?? '-' }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        @if($loan->status == 'pending')
                            <form action="{{ route('user.loans.cancel', $loan->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin batalkan?')">
                                    <i class="fas fa-times me-2"></i> Batalkan
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('user.loans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
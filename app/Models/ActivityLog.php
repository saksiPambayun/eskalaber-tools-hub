<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Label aksi yang tampil di UI dan laporan.
     */
    public function actionLabel(): string
    {
        return match ($this->action) {
            'approve_loan' => 'Setujui Pinjaman',
            'reject_loan' => 'Tolak Pinjaman',
            'cancel_loan' => 'Batalkan Pinjaman',
            'return_loan' => 'Kembalikan Alat',
            'create_loan' => 'Ajukan Pinjaman',
            'submit_fine_payment' => 'Unggah Pembayaran',
            'verify_fine' => 'Verifikasi Pembayaran',
            'pay_fine' => 'Tandai Lunas',
            'update_fine_rate' => 'Ubah Tarif Pembayaran',
            'UPDATE_LOGO_SETTING', 'update_logo_setting' => 'Ubah Pengaturan Logo',
            default => str_replace('_', ' ', ucfirst((string) $this->action)),
        };
    }
}
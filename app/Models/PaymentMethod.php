<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'account_number',
        'account_name',
        'qr_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function qrImageUrl(): ?string
    {
        if (!$this->qr_image) {
            return null;
        }

        if (str_starts_with($this->qr_image, 'http://') || str_starts_with($this->qr_image, 'https://') || str_starts_with($this->qr_image, '/')) {
            return $this->qr_image;
        }

        return asset($this->qr_image);
    }
}

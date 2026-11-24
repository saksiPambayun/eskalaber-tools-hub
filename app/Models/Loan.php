<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tool_id',
        'loan_date',
        'return_date',
        'actual_return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function fine()
    {
        return $this->hasOne(Fine::class);
    }

    public function isLate(): bool
    {
        if (!in_array($this->status, ['approved', 'borrowed', 'late'], true)) {
            return false;
        }

        if (!$this->return_date) {
            return false;
        }

        $compareDate = $this->actual_return_date ?? now();
        return $compareDate->startOfDay()->gt($this->return_date->startOfDay());
    }

    public function getLateDays(): int
    {
        if (!$this->return_date) {
            return 0;
        }

        $compareDate = $this->actual_return_date ?? now();
        if ($compareDate->startOfDay()->lte($this->return_date->startOfDay())) {
            return 0;
        }

        return $this->return_date->startOfDay()->diffInDays($compareDate->startOfDay());
    }
}
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

    public function isLate()
    {
        return $this->status === 'borrowed' && now()->greaterThan($this->return_date);
    }

    public function getLateDays()
    {
        if ($this->actual_return_date) {
            return max(0, $this->actual_return_date->diffInDays($this->return_date));
        }
        return max(0, now()->diffInDays($this->return_date));
    }
}
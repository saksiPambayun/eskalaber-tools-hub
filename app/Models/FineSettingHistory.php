<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FineSettingHistory extends Model
{
    protected $fillable = [
        'fine_setting_id',
        'old_rate',
        'new_rate',
        'changed_by',
    ];

    protected $casts = [
        'old_rate' => 'integer',
        'new_rate' => 'integer',
    ];

    public function setting()
    {
        return $this->belongsTo(FineSetting::class, 'fine_setting_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

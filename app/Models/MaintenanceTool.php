<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTool extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_id',
        'quantity',
        'status',
        'notes',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}

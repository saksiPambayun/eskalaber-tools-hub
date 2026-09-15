<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category_id',
        'type_id',
        'place_id',
        'stock',
        'status',
        'qr_code',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function maintenanceTools()
    {
        return $this->hasMany(MaintenanceTool::class);
    }

    public function isAvailable()
    {
        return $this->status === 'available' && $this->stock > 0;
    }
}
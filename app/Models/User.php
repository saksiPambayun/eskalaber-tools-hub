<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'phone',
        'address',
        'photo', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'SUPERADMIN';
    }

    public function isToolsman()
    {
        return $this->role === 'TOOLSMAN';
    }

    public function isUser()
    {
        return $this->role === 'USER';
    }
}

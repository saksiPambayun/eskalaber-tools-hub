<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: Kirim notifikasi ke user tertentu
    public static function send($userId, $title, $message, $type = 'info', $url = null)
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'url' => $url,
            'is_read' => false,
        ]);
    }

    // Helper: Kirim ke semua admin
    public static function sendToAdmins($title, $message, $type = 'info', $url = null)
    {
        $admins = User::where('role', 'SUPERADMIN')->get();
        foreach ($admins as $admin) {
            self::send($admin->id, $title, $message, $type, $url);
        }
    }

    // Helper: Kirim ke semua toolsman
    public static function sendToToolsmans($title, $message, $type = 'info', $url = null)
    {
        $toolsmans = User::where('role', 'TOOLSMAN')->get();
        foreach ($toolsmans as $toolsman) {
            self::send($toolsman->id, $title, $message, $type, $url);
        }
    }
}

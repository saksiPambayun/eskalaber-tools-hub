<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogoSetting extends Model
{
    use HasFactory;

    public const DEFAULT_TITLE = 'Sarana & Prasarana';
    public const DEFAULT_SUBTITLE = 'Eskalaber Tools Hub';
    public const DEFAULT_IMAGE = 'images/download.jpeg';

    protected $fillable = [
        'image',
        'title',
        'subtitle',
    ];

    /**
     * Satu baris pengaturan global. Belum ada data -> pakai nilai default.
     */
    public static function current(): self
    {
        return static::first() ?? new static([
            'image' => self::DEFAULT_IMAGE,
            'title' => self::DEFAULT_TITLE,
            'subtitle' => self::DEFAULT_SUBTITLE,
        ]);
    }

    public function titleText(): string
    {
        return $this->title ?: self::DEFAULT_TITLE;
    }

    public function subtitleText(): string
    {
        return $this->subtitle ?: self::DEFAULT_SUBTITLE;
    }

    public function imageUrl(): string
    {
        return $this->image ? asset($this->image) : asset(self::DEFAULT_IMAGE);
    }
}

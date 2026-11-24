<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FineSetting extends Model
{
    public const DEFAULT_RATE = 10000;

    protected $fillable = [
        'rate_per_day',
    ];

    protected $casts = [
        'rate_per_day' => 'integer',
    ];

    public function histories()
    {
        return $this->hasMany(FineSettingHistory::class);
    }

    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['rate_per_day' => self::DEFAULT_RATE]
        );
    }

    public static function rate(): int
    {
        return (int) (static::current()->rate_per_day ?? self::DEFAULT_RATE);
    }

    public function changeRate(int $newRate, $actor = null): bool
    {
        return DB::transaction(function () use ($newRate, $actor) {
            $setting = static::whereKey($this->id)->lockForUpdate()->firstOrFail();
            $oldRate = (int) $setting->rate_per_day;

            if ($oldRate === $newRate) {
                return false;
            }

            FineSettingHistory::create([
                'fine_setting_id' => $setting->id,
                'old_rate' => $oldRate,
                'new_rate' => $newRate,
                'changed_by' => $actor?->id,
            ]);

            $setting->update(['rate_per_day' => $newRate]);

            return true;
        });
    }
}

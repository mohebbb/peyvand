<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    protected $fillable = [
        'title',
        'destination_url',
        'is_active',
    ];

    protected $attributes = [
        'clicks' => 0,
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'clicks' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    protected static function booted(): void
    {
        static::creating(function (ShortLink $link) {
            if (empty($link->short_code)) {
                $link->short_code = static::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        $alphabet = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz';

        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
        } while (static::where('short_code', $code)->exists());

        return $code;
    }

    public function trackableUrl(): string
    {
        return rtrim(config('app.url'), '/').'/'.$this->short_code;
    }

    public function incrementClicks(): void
    {
        static::withoutTimestamps(fn () => $this->increment('clicks'));
    }
}

<?php

namespace App\Models;

use App\Services\QrCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class QrCodeSetting extends Model
{
    protected $fillable = [
        'format',
        'size',
        'margin',
        'error_correction',
        'foreground_color',
        'background_color',
        'transparent_background',
        'logo_enabled',
        'logo_path',
        'logo_size_percent',
    ];

    protected $attributes = [
        'format' => 'svg',
        'size' => 300,
        'margin' => 2,
        'error_correction' => 'H',
        'foreground_color' => '#000000',
        'background_color' => '#ffffff',
        'transparent_background' => false,
        'logo_enabled' => false,
        'logo_size_percent' => 20,
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'margin' => 'integer',
            'transparent_background' => 'boolean',
            'logo_enabled' => 'boolean',
            'logo_size_percent' => 'integer',
        ];
    }

    /**
     * The settings are a single (singleton) row shared by the whole panel.
     */
    public static function current(): self
    {
        return static::query()->first() ?? static::create([]);
    }

    /**
     * The settings in the shape expected by QrCodeGenerator::generate().
     *
     * @return array<string, mixed>
     */
    public function toGeneratorSettings(): array
    {
        return [
            'format' => $this->format,
            'size' => $this->size,
            'margin' => $this->margin,
            'error_correction' => $this->error_correction,
            'foreground_color' => $this->foreground_color,
            'background_color' => $this->background_color,
            'transparent_background' => $this->transparent_background,
            'logo_enabled' => $this->logo_enabled,
            'logo_path' => $this->logo_path,
            'logo_size_percent' => $this->logo_size_percent,
        ];
    }

    /**
     * Persists new settings and deletes a replaced logo file.
     *
     * @param  array<string, mixed>  $settings
     */
    public function applySettings(array $settings): void
    {
        $oldLogo = $this->logo_path;

        $this->fill($settings)->save();

        if (filled($oldLogo) && $this->logo_path !== $oldLogo) {
            Storage::disk(QrCodeGenerator::logoDisk())->delete($oldLogo);
        }
    }
}

<?php

namespace App\Filament\Schemas;

use App\Models\QrCodeSetting;
use App\Services\QrCodeGenerator;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Throwable;

class QrCodeSettingsSchema
{
    /**
     * The shared QR code download settings, used by both the row action
     * modal and the "QR code defaults" settings page.
     *
     * @return array<int, mixed>
     */
    public static function schema(bool $withSaveDefaultToggle = false, bool $withPreview = false): array
    {
        $default = fn (string $attribute): callable => fn (): mixed => QrCodeSetting::current()->{$attribute};

        $components = [];

        if ($withPreview) {
            $components = [
                self::previewField(),
                TextInput::make('preview_url')
                    ->label('Preview URL')
                    ->url()
                    ->default(fn (): string => rtrim((string) config('app.url'), '/').'/preview')
                    ->live(debounce: 500)
                    ->helperText('Only used for the preview image above.')
                    ->columnSpanFull(),
            ];
        }

        $components = [
            ...$components,
            Grid::make(2)->schema([
                ToggleButtons::make('format')
                    ->label('Format')
                    ->options(['svg' => 'SVG', 'png' => 'PNG', 'jpg' => 'JPG'])
                    ->inline()
                    ->default($default('format'))
                    ->required()
                    ->live(),
                Select::make('error_correction')
                    ->label('Error correction')
                    ->options([
                        'L' => 'L — low (7%)',
                        'M' => 'M — medium (15%)',
                        'Q' => 'Q — quartile (25%)',
                        'H' => 'H — high (30%)',
                    ])
                    ->default($default('error_correction'))
                    ->required()
                    ->live(),
            ]),
            Grid::make(2)->schema([
                Slider::make('size')
                    ->label('Size')
                    ->minValue(100)
                    ->maxValue(1000)
                    ->step(10)
                    ->helperText('Width of the image in pixels.')
                    ->default($default('size'))
                    ->required()
                    ->live(),
                Slider::make('margin')
                    ->label('Quiet zone (margin)')
                    ->minValue(0)
                    ->maxValue(10)
                    ->step(1)
                    ->helperText('Border around the code, in modules.')
                    ->default($default('margin'))
                    ->required()
                    ->live(),
            ]),
            Grid::make(2)->schema([
                ColorPicker::make('foreground_color')
                    ->label('Foreground color')
                    ->default($default('foreground_color'))
                    ->required()
                    ->live(),
                ColorPicker::make('background_color')
                    ->label('Background color')
                    ->default($default('background_color'))
                    ->required()
                    ->live(),
            ]),
            Toggle::make('transparent_background')
                ->label('Transparent background')
                ->hint('PNG & SVG only')
                ->hidden(fn (Get $get): bool => $get('format') === 'jpg')
                ->default($default('transparent_background'))
                ->live(),
            Toggle::make('logo_enabled')
                ->label('Logo')
                ->default($default('logo_enabled'))
                ->live(),
            FileUpload::make('logo_path')
                ->label('Logo image')
                ->image()
                ->disk(QrCodeGenerator::logoDisk())
                ->directory(QrCodeGenerator::logoDirectory())
                ->maxSize(1024)
                ->maxFiles(1)
                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp', 'image/gif'])
                ->helperText('Placed at the center on a white plate; keep it small for reliable scanning.')
                ->visible(fn (Get $get): bool => (bool) $get('logo_enabled'))
                ->default($default('logo_path')),
            Slider::make('logo_size_percent')
                ->label('Logo size')
                ->minValue(10)
                ->maxValue(30)
                ->step(1)
                ->helperText('Percentage of the QR code width.')
                ->visible(fn (Get $get): bool => (bool) $get('logo_enabled'))
                ->default($default('logo_size_percent'))
                ->required()
                ->live(),
        ];

        if ($withSaveDefaultToggle) {
            $components[] = Toggle::make('save_as_default')
                ->label('Save as default')
                ->helperText('Use these settings for the quick download button.')
                ->default(false);
        }

        return $components;
    }

    private static function previewField(): Placeholder
    {
        return Placeholder::make('preview')
            ->hiddenLabel()
            ->content(function (Get $get): Htmlable {
                try {
                    $settings = [];

                    foreach (array_keys(QrCodeSetting::current()->toGeneratorSettings()) as $key) {
                        $settings[$key] = $get($key);
                    }

                    $text = filled($get('preview_url')) ? (string) $get('preview_url') : (string) config('app.url');
                    $qr = app(QrCodeGenerator::class)->generate($text, $settings);

                    return new HtmlString(
                        '<img src="data:image/svg+xml;base64,'.base64_encode($qr->content)
                        .'" alt="QR code preview" style="width: 180px; height: 180px;">',
                    );
                } catch (Throwable) {
                    return new HtmlString('<span style="color: #ef4444;">The preview could not be rendered.</span>');
                }
            })
            ->columnSpanFull();
    }
}

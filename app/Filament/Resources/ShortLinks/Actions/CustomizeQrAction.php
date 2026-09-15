<?php

namespace App\Filament\Resources\ShortLinks\Actions;

use App\Filament\Schemas\QrCodeSettingsSchema;
use App\Models\QrCodeSetting;
use App\Models\ShortLink;
use App\Services\QrCodeGenerator;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;

class CustomizeQrAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'customizeQr';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->iconButton()
            ->label(__('qr_codes.actions.customize'))
            ->icon(Heroicon::OutlinedChevronDown)
            ->color('gray')
            ->tooltip(__('qr_codes.actions.customize_tooltip'))
            ->modalHeading(__('qr_codes.actions.customize_heading'))
            ->modalDescription(__('qr_codes.actions.customize_description'))
            ->modalSubmitActionLabel(__('qr_codes.actions.download_submit'))
            ->schema(fn (): array => QrCodeSettingsSchema::schema(withSaveDefaultToggle: true))
            ->action(function (array $data, ShortLink $record) {
                $settings = Arr::except($data, 'save_as_default');

                if ($data['save_as_default'] ?? false) {
                    QrCodeSetting::current()->applySettings($settings);
                }

                $file = app(QrCodeGenerator::class)->generate($record->trackableUrl(), $settings);

                return response()->streamDownload(
                    fn (): string => print $file->content,
                    "qr-{$record->short_code}.{$file->extension}",
                    ['Content-Type' => $file->contentType],
                );
            });
    }
}

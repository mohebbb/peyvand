<?php

namespace App\Filament\Resources\ShortLinks\Actions;

use App\Models\QrCodeSetting;
use App\Models\ShortLink;
use App\Services\QrCodeGenerator;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

class DownloadQrAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'downloadQr';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->iconButton()
            ->label('Download QR code')
            ->icon(Heroicon::OutlinedQrCode)
            ->color('gray')
            ->tooltip('Download QR code (default settings)')
            ->action(function (ShortLink $record) {
                $file = app(QrCodeGenerator::class)->generate(
                    $record->trackableUrl(),
                    QrCodeSetting::current()->toGeneratorSettings(),
                );

                return response()->streamDownload(
                    fn (): string => print $file->content,
                    "qr-{$record->short_code}.{$file->extension}",
                    ['Content-Type' => $file->contentType],
                );
            });
    }
}

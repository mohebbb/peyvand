<?php

namespace App\Filament\Resources\ShortLinks\Actions;

use App\Models\ShortLink;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            ->tooltip('Download QR code (SVG)')
            ->action(function (ShortLink $record) {
                $svg = QrCode::format('svg')
                    ->size(300)
                    ->margin(2)
                    ->errorCorrection('H')
                    ->generate($record->trackableUrl());

                $filename = 'qr-'.$record->short_code.'.svg';

                return response()->streamDownload(
                    function () use ($svg) {
                        echo $svg;
                    },
                    $filename,
                    ['Content-Type' => 'image/svg+xml'],
                );
            });
    }
}

<?php

namespace App\Filament\Resources\ShortLinks\Actions;

use App\Models\ShortLink;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Js;

class CopyLinkAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'copyLink';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->iconButton()
            ->label(__('qr_codes.actions.copy_link'))
            ->icon(Heroicon::OutlinedClipboardDocument)
            ->color('gray')
            ->tooltip(__('qr_codes.actions.copy_link'))
            ->alpineClickHandler(
                fn (ShortLink $record): string => 'window.navigator.clipboard.writeText('
                    .Js::from($record->trackableUrl())
                    .'); new FilamentNotification().title('.Js::from(__('short_links.messages.link_copied')).').success().send()',
            );
    }
}

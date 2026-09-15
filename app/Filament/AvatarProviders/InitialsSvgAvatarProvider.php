<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class InitialsSvgAvatarProvider implements AvatarProvider
{
    public function get(Model|Authenticatable $record): string
    {
        $initials = str(Filament::getNameForDefaultAvatar($record))
            ->trim()
            ->explode(' ')
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join(' ');

        $background = Color::convertToHex(FilamentColor::getColor('gray')[950] ?? Color::Gray[950]);

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">'
            .'<rect width="100" height="100" rx="20" fill="'.htmlspecialchars($background).'"/>'
            .'<text x="50" y="50" dy=".36em" text-anchor="middle" '
            .'font-family="Inter Variable, ui-sans-serif, system-ui, sans-serif" font-size="42" '
            .'font-weight="600" fill="#FFFFFF">'.htmlspecialchars($initials).'</text>'
            .'</svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}

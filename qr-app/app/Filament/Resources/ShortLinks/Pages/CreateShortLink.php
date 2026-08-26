<?php

namespace App\Filament\Resources\ShortLinks\Pages;

use App\Filament\Resources\ShortLinks\ShortLinkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShortLink extends CreateRecord
{
    protected static string $resource = ShortLinkResource::class;
}

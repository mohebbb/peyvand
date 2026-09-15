<?php

namespace App\Filament\Resources\ShortLinks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ShortLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('short_links.fields.title'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('destination_url')
                    ->label(__('short_links.fields.destination_url'))
                    ->required()
                    ->url()
                    ->maxLength(2048)
                    ->helperText(__('short_links.fields.destination_url_helper'))
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label(__('short_links.fields.is_active'))
                    ->default(true)
                    ->required(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\ShortLinks\Tables;

use App\Filament\Resources\ShortLinks\Actions\CopyLinkAction;
use App\Filament\Resources\ShortLinks\Actions\CustomizeQrAction;
use App\Filament\Resources\ShortLinks\Actions\DownloadQrAction;
use App\Models\ShortLink;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ShortLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('short_links.fields.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('short_code')
                    ->label(__('short_links.fields.short_code'))
                    ->copyable()
                    ->copyMessage(__('short_links.messages.short_url_copied'))
                    ->badge()
                    ->fontFamily('ui-monospace')
                    ->color('info'),
                TextColumn::make('destination_url')
                    ->label(__('short_links.fields.destination'))
                    ->limit(45)
                    ->tooltip(fn (ShortLink $record): string => $record->destination_url),
                TextColumn::make('clicks')
                    ->label(__('short_links.fields.clicks'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                IconColumn::make('is_active')
                    ->label(__('short_links.fields.is_active'))
                    ->boolean()
                    ->action(
                        fn (ShortLink $record) => $record->update(['is_active' => ! $record->is_active])
                    )
                    ->tooltip(fn (ShortLink $record): string => $record->is_active
                        ? __('short_links.messages.click_to_deactivate')
                        : __('short_links.messages.click_to_activate')),
                TextColumn::make('created_at')
                    ->label(__('short_links.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('short_links.fields.is_active')),
            ])
            ->recordActions([
                DownloadQrAction::make(),
                CustomizeQrAction::make(),
                CopyLinkAction::make(),
                EditAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

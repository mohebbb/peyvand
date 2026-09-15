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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('short_code')
                    ->label('Code')
                    ->copyable()
                    ->copyMessage('Short URL copied')
                    ->badge()
                    ->fontFamily('ui-monospace')
                    ->color('info'),
                TextColumn::make('destination_url')
                    ->label('Destination')
                    ->limit(45)
                    ->tooltip(fn (ShortLink $record): string => $record->destination_url),
                TextColumn::make('clicks')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->action(
                        fn (ShortLink $record) => $record->update(['is_active' => ! $record->is_active])
                    )
                    ->tooltip(fn (ShortLink $record): string => $record->is_active ? 'Click to deactivate' : 'Click to activate'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
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

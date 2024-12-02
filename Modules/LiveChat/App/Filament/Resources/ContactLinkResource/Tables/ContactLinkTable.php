<?php

namespace Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Tables\Actions\ContactLinkAction;
use Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Tables\BulkActions\ContactLinkBulkAction;
use Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Tables\Filters\ContactLinkFilter;

class ContactLinkTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('facebook_messenger_link')
                    ->label('Facebook Messenger')
                    ->url(fn($record) => $record->facebook_messenger_link)
                    ->openUrlInNewTab()
                    ->formatStateUsing(fn($state) => substr($state, 0, 20) . '...')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('zalo_link')
                    ->label('Zalo')
                    ->url(fn($record) => $record->zalo_link)
                    ->openUrlInNewTab()
                    ->formatStateUsing(fn($state) => substr($state, 0, 20) . '...')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone_number')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                TextColumn::make('text_color')
                    ->label('Màu chữ')
                    ->formatStateUsing(fn($state) => "<div style='width: 20px; height: 20px; border-radius: 50%; background-color: {$state};'></div>")
                    ->html(),
                TextColumn::make('position')
                    ->label('Vị trí nút')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        $options = [
                            'bottom-left' => 'Dưới cùng bên trái',
                            'bottom-right' => 'Dưới cùng bên phải',
                        ];
                        return Arr::get($options, $state, $state);
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions(ContactLinkAction::action())
            ->bulkActions(ContactLinkBulkAction::bulkActions());
    }
}

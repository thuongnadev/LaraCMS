<?php

namespace Modules\Form\App\Filament\Resources\EmailConfigResource\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Form\App\Filament\Resources\EmailConfigResource\Tables\Actions\EmailConfigAction;
use Modules\Form\App\Filament\Resources\EmailConfigResource\Tables\BulkActions\EmailConfigBulkAction;
use Modules\Form\App\Filament\Resources\EmailConfigResource\Tables\Filters\EmailConfigFilter;

class EmailConfigTable
{
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('mailer_type')->label('Loại Mailer')->sortable(),
            IconColumn::make('is_default')->boolean()->label('Mặc định')->sortable(),
            TextColumn::make('created_at')->label('Ngày thêm')->dateTime()->sortable(),
        ])
            ->filters(EmailConfigFilter::filter())
            ->actions(EmailConfigAction::action())
            ->bulkActions(EmailConfigBulkAction::bulkActions());
    }
}
<?php

namespace Modules\User\App\Filament\Resources\UserResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\User\App\Filament\Resources\UserResource\Tables\Actions\UserAction;
use Modules\User\App\Filament\Resources\UserResource\Tables\Actions\UserBulkAction;
use Modules\User\App\Filament\Resources\UserResource\Tables\Filters\UserFilter;

class UserTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên người dùng')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Vai trò')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters(UserFilter::filter())
            ->actions(UserAction::action())
            ->bulkActions(UserBulkAction::bulkActions());
    }
}

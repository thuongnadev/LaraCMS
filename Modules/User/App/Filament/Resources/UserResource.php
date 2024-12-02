<?php

namespace Modules\User\App\Filament\Resources;

use Modules\User\App\Filament\Resources\UserResource\Forms\UserForm;
use Modules\User\App\Filament\Resources\UserResource\Tables\UserTable;
use App\Models\User;
use Modules\User\App\Filament\Resources\UserResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Phân quyền';

    public static function getNavigationLabel(): string
    {
        return 'Người dùng';
    }

    public static function getModelLabel(): string
    {
        return 'Người dùng';
    }

    public static function getPluraModelLabel(): string
    {
        return 'Người dùng';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return UserForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return UserTable::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

<?php

namespace Modules\Form\App\Filament\Resources;

use Modules\Form\App\Filament\Resources\FormNotificationResource\Forms\FormNotificationForm;
use Modules\Form\App\Filament\Resources\FormNotificationResource\Tables\FormNotificationTable;
use Modules\Form\App\Filament\Resources\FormNotificationResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Form\Entities\FormNotification;

class FormNotificationResource extends Resource
{
    protected static ?string $model = FormNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationGroup = 'Biểu mẫu';


    public static function getNavigationLabel(): string
    {
        return 'Thông báo và chú ý';
    }

    public static function getModelLabel(): string
    {
        return 'Thông báo';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Thông báo và chú ý';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return FormNotificationForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return FormNotificationTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormNotification::route('/'),
            'create' => Pages\CreateFormNotification::route('/create'),
            'edit' => Pages\EditFormNotification::route('/{record}/edit'),
        ];
    }
}
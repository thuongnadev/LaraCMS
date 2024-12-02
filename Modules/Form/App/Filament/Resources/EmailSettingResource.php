<?php

namespace Modules\Form\App\Filament\Resources;

use Modules\Form\App\Filament\Resources\EmailSettingResource\Forms\EmailSettingForm;
use Modules\Form\App\Filament\Resources\EmailSettingResource\Tables\EmailSettingTable;
use Modules\Form\App\Filament\Resources\EmailSettingResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Form\Entities\EmailSetting;

class EmailSettingResource extends Resource
{
    protected static ?string $model = EmailSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Biểu mẫu';


    public static function getNavigationLabel(): string
    {
        return 'Biểu mẫu';
    }

    public static function getModelLabel(): string
    {
        return 'Biểu mẫu';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Biểu mẫu';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return EmailSettingForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return EmailSettingTable::table($table);
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
            'index' => Pages\ListEmailSetting::route('/'),
            'create' => Pages\CreateEmailSetting::route('/create'),
            'edit' => Pages\EditEmailSetting::route('/{record}/edit'),
        ];
    }
}
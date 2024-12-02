<?php

namespace Modules\Form\App\Filament\Resources;

use Modules\Form\App\Filament\Resources\EmailConfigResource\Forms\EmailConfigForm;
use Modules\Form\App\Filament\Resources\EmailConfigResource\Tables\EmailConfigTable;
use Modules\Form\App\Filament\Resources\EmailConfigResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Form\Entities\EmailConfig;

class EmailConfigResource extends Resource
{
    protected static ?string $model = EmailConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-at-symbol';

    protected static ?string $navigationGroup = 'Biểu mẫu';

    public static function getNavigationLabel(): string
    {
        return 'Cấu hình Email';
    }

    public static function getModelLabel(): string
    {
        return 'Cấu hình Email';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Cấu hình Email';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return EmailConfigForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return EmailConfigTable::table($table);
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
            'index' => Pages\ListEmailConfig::route('/'),
            'create' => Pages\CreateEmailConfig::route('/create'),
            'edit' => Pages\EditEmailConfig::route('/{record}/edit'),
        ];
    }
}
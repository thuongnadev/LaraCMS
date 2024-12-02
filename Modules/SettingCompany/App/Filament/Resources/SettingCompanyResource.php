<?php

namespace Modules\SettingCompany\App\Filament\Resources;

use Modules\SettingCompany\App\Filament\Resources\SettingCompanyResource\Forms\SettingCompanyForm;
use Modules\SettingCompany\App\Filament\Resources\SettingCompanyResource\Tables\SettingCompanyTable;
use Modules\SettingCompany\App\Filament\Resources\SettingCompanyResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\SettingCompany\Entities\Business;

class SettingCompanyResource extends Resource
{
    protected static ?string $model = Business::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Cấu hình thông tin';

    public static function getNavigationLabel(): string
    {
        return 'Thông tin công ty';
    }

    public static function getModelLabel(): string
    {
        return 'Thông tin công ty';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Thông tin công ty';
    }
    
    public static function form(Form $form): Form
    {
        return SettingCompanyForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return SettingCompanyTable::table($table);
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
            'index' => Pages\EditSettingCompany::route('/edit'),
        ];
    }
}
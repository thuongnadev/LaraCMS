<?php

namespace Modules\Setting\App\Filament\Resources;

use Modules\Setting\App\Filament\Resources\SettingResource\Forms\SettingForm;
use Modules\Setting\App\Filament\Resources\SettingResource\Tables\SettingTable;
use Modules\Setting\App\Filament\Resources\SettingResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Setting\App\Filament\Resources\SettingResource\Pages\SettingPanel;

class SettingResource extends Resource
{
    protected static ?string $model = null;

    protected static ?string $slug = 'theme';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    public static function getNavigationLabel(): string
    {
        return 'Cấu Hình';
    }

    public static function getModelLabel(): string
    {
        return 'Cấu Hình';
    }

    public static function getPluraModelLabel(): string
    {
        return 'Cấu Hình';
    }

    public static function form(Form $form): Form
    {
        return SettingForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return SettingTable::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => SettingPanel::route('/setting'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}


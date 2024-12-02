<?php

namespace Modules\Component\App\Filament\Resources;

use Modules\Component\App\Filament\Resources\ComponentResource\Forms\ComponentForm;
use Modules\Component\App\Filament\Resources\ComponentResource\Tables\ComponentTable;
use Modules\Component\App\Filament\Resources\ComponentResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Component\Entities\Component as EntitiesComponent;

class ComponentResource extends Resource
{
    protected static ?string $model = EntitiesComponent::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $navigationGroup = 'Cấu hình web';

    public static function getNavigationLabel(): string
    {
        return 'Thành phần';
    }

    public static function getModelLabel(): string
    {
        return 'Thành phần';
    }

    public static function getPluraModelLabel(): string
    {
        return 'Thành phần';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return ComponentForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return ComponentTable::table($table);
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
            'index' => Pages\ListComponent::route('/'),
            'create' => Pages\CreateComponent::route('/create'),
            'edit' => Pages\EditComponent::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}

<?php

namespace Modules\Form\App\Filament\Resources;

use Modules\Form\App\Filament\Resources\FormResource\Forms\FormForm;
use Modules\Form\App\Filament\Resources\FormResource\Tables\FormTable;
use Modules\Form\App\Filament\Resources\FormResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Form\Entities\Form;

class FormResource extends Resource
{
    protected static ?string $model = Form::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = 'Biểu mẫu';

    
    public static function getNavigationLabel(): string
    {
        return 'Tạo biểu mẫu';
    }
    
    public static function getModelLabel(): string
    {
        return 'Tạo biểu mẫu';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tạo biểu mẫu';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return FormForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return FormTable::table($table);
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
            'index' => Pages\ListForm::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }
}
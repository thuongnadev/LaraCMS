<?php

namespace Modules\Tag\App\Filament\Resources;

use Modules\Tag\App\Filament\Resources\TaggableResource\Forms\TaggableForm;
use Modules\Tag\App\Filament\Resources\TaggableResource\Tables\TaggableTable;
use Modules\Tag\Entities\Taggable;
use Modules\Tag\App\Filament\Resources\TaggableResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class TaggableResource extends Resource
{
    protected static ?string $model = Taggable::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    public static function getNavigationLabel(): string
    {
        return 'Taggable';
    }

    public static function getModelLabel(): string
    {
        return 'Taggable';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Taggable';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return TaggableForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return TaggableTable::table($table);
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
            'index' => Pages\ListTaggable::route('/'),
            'create' => Pages\CreateTaggable::route('/create'),
            'edit' => Pages\EditTaggable::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}

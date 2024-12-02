<?php

namespace Modules\Tag\App\Filament\Resources;

use Modules\Tag\App\Filament\Resources\TagResource\Forms\TagForm;
use Modules\Tag\App\Filament\Resources\TagResource\Tables\TagTable;
use Modules\Tag\Entities\Tag;
use Modules\Tag\App\Filament\Resources\TagResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Nội dung';

    public static function getNavigationLabel(): string
    {
        return 'Thẻ';
    }

    public static function getModelLabel(): string
    {
        return 'Thẻ';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Thẻ';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return TagForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return TagTable::table($table);
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
            'index' => Pages\ListTag::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}

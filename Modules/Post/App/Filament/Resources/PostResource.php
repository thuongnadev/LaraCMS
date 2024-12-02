<?php

namespace Modules\Post\App\Filament\Resources;

use Modules\Post\App\Filament\Resources\PostResource\Forms\PostForm;
use Modules\Post\App\Filament\Resources\PostResource\Tables\PostTable;
use Modules\Post\App\Filament\Resources\PostResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Post\Entities\Post;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Nội dung';

    public static function getNavigationLabel(): string
    {
        return 'Bài viết';
    }

    public static function getModelLabel(): string
    {
        return 'Bài viết';
    }

    public static function getPluraModelLabel(): string
    {
        return 'Bài viết';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return PostForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return PostTable::table($table);
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            'view' => Pages\ViewPost::route('/{record}'),
        ];
    }
}

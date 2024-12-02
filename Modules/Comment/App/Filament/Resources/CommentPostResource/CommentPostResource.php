<?php

namespace Modules\Comment\App\Filament\Resources\CommentPostResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Comment\App\Filament\Resources\CommentPostResource\Tables\Actions\CommentPostInfolist;
use Modules\Comment\App\Filament\Resources\CommentPostResource\Forms\CommentPostForm;
use Modules\Comment\App\Filament\Resources\CommentPostResource\Tables\CommentPostTable;
use Modules\Comment\Entities\Comment;

class CommentPostResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $slug = 'binh-luan-bai-viet';

    public static function getNavigationLabel(): string
    {
        return 'Bình luận bài viết';
    }

    public static function getModelLabel(): string
    {
        return 'Bình luận bài viết';
    }

    public static function getPluraModelLabel(): string
    {
        return 'Bình luận bài viết';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('commentable_type', 'Modules\Post\Entities\Post')->count();
    }

    public static function form(Form $form): Form
    {
        return CommentPostForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return CommentPostTable::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return CommentPostInfolist::infolist($infolist);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommentPost::route('/'),
            'create' => Pages\CreateCommentPost::route('/create'),
            'view' => Pages\ViewCommentPost::route('/{record}'),
            'edit' => Pages\EditCommentPost::route('/{record}/edit'),
        ];
    }
}

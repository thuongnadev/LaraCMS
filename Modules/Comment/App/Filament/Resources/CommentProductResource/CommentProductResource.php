<?php

namespace Modules\Comment\App\Filament\Resources\CommentProductResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Comment\App\Filament\Resources\CommentProductResource\Forms\CommentProductForm;
use Modules\Comment\App\Filament\Resources\CommentProductResource\Tables\Actions\CommentProductInfolist;
use Modules\Comment\App\Filament\Resources\CommentProductResource\Tables\CommentProductTable;
use Modules\Comment\Entities\Comment;

class CommentProductResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $slug = 'binh-luan-san-pham';
    public static function getNavigationLabel(): string
    {
        return 'Đánh giá sản phẩm';
    }

    public static function getModelLabel(): string
    {
        return 'Đánh giá sản phẩm';
    }

    public static function getPluraModelLabel(): string
    {
        return 'Đánh giá sản phẩm';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('commentable_type', 'Modules\Product\Entities\Product')->count();
    }

    public static function form(Form $form): Form
    {
        return CommentProductForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return CommentProductTable::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return CommentProductInfolist::infolist($infolist);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommentProducts::route('/'),
            'create' => Pages\CreateCommentProduct::route('/create'),
            'view' => Pages\ViewCommentProduct::route('/{record}'),
            'edit' => Pages\EditCommentProduct::route('/{record}/edit'),
        ];
    }
}

<?php

namespace Modules\Comment\App\Filament\Resources\CommentResource;

use Filament\Resources\Resource;
use Modules\Comment\Entities\Comment;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;
    protected static ?string $slug = 'phan-tich-binh-luan';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left';

    public static function getNavigationLabel(): string
    {
        return 'Phân tích bình luận';
    }

    public static function getModelLabel(): string
    {
        return 'Phân tích bình luận';
    }

    public static function getPluraModelLabel(): string
    {
        return 'Phân tích bình luận';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\StatisticalComment::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}

<?php

namespace Modules\Comment\App\Filament\Resources\CommentProductResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Modules\Comment\App\Filament\Resources\CommentProductResource\CommentProductResource;

class ListCommentProducts extends ListRecords
{
    protected static string $resource = CommentProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('Bình luận bài viết')
                ->button()
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('primary'),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Tất cả'),

            'on' => Tab::make('Hiển thị')
                ->icon('heroicon-o-eye')
                ->query(fn ($query) => $query->where('show', true)),

            'off' => Tab::make('Ẩn')
                ->icon('heroicon-o-eye-slash')
                ->query(fn ($query) => $query->where('show', false)),

            'replies' => Tab::make('Có phản hồi')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->query(fn (Builder $query) => $query->whereHas('replies')),
        ];
    }
}

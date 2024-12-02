<?php

namespace Modules\Comment\App\Filament\Resources\CommentPostResource\Tables;

use Archilex\ToggleIconColumn\Columns\ToggleIconColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Modules\Comment\App\Filament\Resources\CommentPostResource\CommentPostResource;
use Modules\Comment\App\Filament\Resources\CommentPostResource\Tables\Actions\CommentPostAction;
use Modules\Comment\App\Filament\Resources\CommentPostResource\Tables\Filters\CommentPostFilter;
use Modules\Comment\Entities\Comment;

class CommentPostTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Comment::query()->where('commentable_type', 'Modules\Post\Entities\Post')
                    ->whereNotNull('commentable_id')
            )
            ->columns([
                TextColumn::make('commentable.title')
                    ->label('Bài viết')
                    ->wrap()
                    ->limit(50)
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('account.name')
                    ->label('Người bình luận')
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('text')
                    ->label('Nội dung')
                    ->wrap()
                    ->limit(50)
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                ToggleIconColumn::make('show')
                    ->label('Trạng thái')
                    ->tooltip(function ($record) {
                        return $record->show ? 'Hiển thị' : 'Ẩn';
                    })
                    ->onIcon('heroicon-o-eye')
                    ->offIcon('heroicon-o-eye-slash')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('replies_count')->counts('replies')
                    ->label('Số người phản hồi')
                    ->wrap()
                    ->badge()
                    ->color('warning')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhập')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->emptyStateActions([
                Action::make('create')
                    ->label('Tạo bình luận bài viết')
                    ->url(CommentPostResource::getUrl('create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters(CommentPostFilter::fillter(), layout: FiltersLayout::Modal)
            ->persistFiltersInSession()
            ->deselectAllRecordsWhenFiltered(false)
            ->filtersFormWidth('4xl')
            ->filtersFormColumns(12)
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Bộ lọc'),
            )
            ->actions(CommentPostAction::action())
            ->bulkActions(CommentPostAction::bulkActions());
    }
}

<?php

namespace Modules\Post\App\Filament\Resources\PostResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Media\Traits\ImageDisplay;
use Modules\Post\App\Filament\Resources\PostResource\Tables\Actions\PostAction;
use Modules\Post\App\Filament\Resources\PostResource\Tables\Actions\PostBulkAction;
use Modules\Post\App\Filament\Resources\PostResource\Tables\Filters\PostFilter;

class PostTable
{
    use ImageDisplay;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Người đăng')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tags.name')
                    ->label('Thẻ')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('categories.name')
                    ->label('Danh mục')
                    ->sortable()
                    ->searchable(),
                self::getImageColumn('post_image', 'Hình ảnh', 50)()
                    ->square(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'warning',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Nháp',
                        'published' => 'Xuất bản',
                        'archived' => 'Lưu trữ',
                        default => ucfirst($state),
                    })
                    ->icon(fn(string $state): ?string => match ($state) {
                        'draft' => 'heroicon-s-pencil-square',
                        'published' => 'heroicon-s-check-circle',
                        'archived' => 'heroicon-s-archive-box',
                        default => null,
                    })
                    ->tooltip(fn(string $state): ?string => match ($state) {
                        'draft' => 'Bài viết hiện đang là bản nháp.',
                        'published' => 'Bài viết này đã được xuất bản.',
                        'archived' => 'Bài viết này đã được lưu trữ.',
                        default => null,
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->sortable(),
                
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters(PostFilter::filter())
            ->actions(PostAction::action())
            ->bulkActions(PostBulkAction::bulkActions());
    }
}

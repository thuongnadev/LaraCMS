<?php

namespace Modules\Media\App\Filament\Resources\MediaResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Modules\Media\App\Filament\Resources\MediaResource\Tables\Actions\MediaAction;
use Modules\Media\App\Filament\Resources\MediaResource\Tables\Actions\MediaBulkAction;
use Modules\Media\App\Filament\Resources\MediaResource\Tables\Filters\MediaFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Session;

class MediaTable
{
    public static function table(Table $table): Table
    {
        $layout = request()->input('tableFilters.layout', Session::get('media_table_layout', 'grid'));
        Session::put('media_table_layout', $layout);
        
        return $table
            ->defaultSort('updated_at', 'desc')
            ->headerActions([
                self::createHeaderAction('grid', 'Lưới', 'heroicon-o-squares-2x2', $layout),
                self::createHeaderAction('list', 'Danh sách', 'heroicon-o-list-bullet', $layout)
            ])
            ->columns(self::getColumns($layout))
            ->filters(MediaFilter::filter())
            ->bulkActions(MediaBulkAction::bulkActions())
            ->when($layout === 'grid', function ($table) {
                return $table
                    ->contentGrid(['sm' => 2, 'md' => 3, 'xl' => 4])
                    ->defaultPaginationPageOption(12)
                    ->paginationPageOptions([12, 24, 48, 96])
                    ->actions(MediaAction::actionGrid());
            }, function ($table) {
                return $table->actions(MediaAction::action());
            });
    }

    protected static function getColumns(string $layout): array
    {
        return $layout === 'grid'
            ? self::getGridColumns()
            : self::getListColumns();
    }

    protected static function getGridColumns(): array
    {
        return [
            Stack::make([
                self::createImageColumn('file_path', '200px', '200px'),
            ]),
            Stack::make([
                TextColumn::make('name')->label('')->searchable(),
                TextColumn::make('file_name')->label('')->searchable(),
                TextColumn::make('mime_type')->label('')->searchable(),
                TextColumn::make('file_type')->label('')->searchable(),
            ])->hidden(fn() => true),
        ];
    }

    protected static function getListColumns(): array
    {
        return [
            self::createImageColumn('file_path', '200px', '200px')->label('Ảnh/File'),
            TextColumn::make('name')->label('Tên file')->searchable()->sortable(),
            TextColumn::make('file_type')->label('Loại file')->searchable()->sortable(),
            TextColumn::make('file_size')
                ->label('Kích thước')
                ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state / 1024, 2) . ' KB' : ($state ?: 'N/A'))
                ->sortable(),
            TextColumn::make('created_at')->label('Ngày thêm')->date('d/m/Y')->sortable(),
        ];
    }

    protected static function createHeaderAction(string $name, string $label, string $icon, string $currentLayout): Action
    {
        return Action::make($name)
            ->label($label)
            ->icon($icon)
            ->action(fn() => redirect()->route('filament.admin.resources.media.index', array_merge(
                request()->query(),
                ['tableFilters' => ['layout' => $name]]
            )))
            ->color($currentLayout === $name ? 'primary' : 'gray');
    }

    protected static function createImageColumn(string $name, string $width, string $height): ImageColumn
    {
        return ImageColumn::make($name)
            ->width($width)
            ->height('auto')
            ->square(false)
            ->label('')
            ->extraImgAttributes(fn ($record) => [
                'class' => 'object-contain w-full h-full rounded',
                'alt' => $record ? $record->name : 'Hình ảnh mặc định',
                'style' => 'max-width: 100%; max-height: 100%;',
            ])
            ->extraAttributes([
                'class' => 'overflow-auto',
                'style' => "width: $width; height: $height;",
            ])
            ->defaultImageUrl(fn ($record) => $record ? asset('storage/' . $record) : asset('img-default.jpg'))
            ->searchable();
    }
}
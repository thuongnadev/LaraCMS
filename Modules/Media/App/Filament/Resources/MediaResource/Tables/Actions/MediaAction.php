<?php

namespace Modules\Media\App\Filament\Resources\MediaResource\Tables\Actions;

use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Modules\Media\Traits\DeletesMedia;

class MediaAction
{

    use DeletesMedia;

    public static function action()
    {
        return [
            ActionGroup::make([
                ViewAction::make()
                    ->label('Xem chi tiết')
                    ->form(self::getFormSchema(true))
                    ->modalWidth(MaxWidth::SevenExtraLarge),
                EditAction::make()
                    ->label('Cập nhật'),
                self::getDeleteAction(),
                Action::make('download')
                    ->label('Tải về')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        $file_path = storage_path('app/public/' . $record->file_path);
                        if (!file_exists($file_path)) {
                            Notification::make()
                                ->title('Lỗi')
                                ->body('Tệp không tồn tại hoặc đã bị xóa.')
                                ->danger()
                                ->send();

                            return;
                        }
                        return response()->download($file_path);
                    })
            ])
        ];
    }

    public static function actionGrid()
    {
        return [
            self::getDeleteAction(),

            Action::make('download')
                ->label('Tải về')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function ($record) {
                    $file_path = storage_path('app/public/' . $record->file_path);
                    if (!file_exists($file_path)) {
                        Notification::make()
                            ->title('Lỗi')
                            ->body('Tệp không tồn tại hoặc đã bị xóa.')
                            ->danger()
                            ->send();
                        return;
                    }
                    return response()->download($file_path);
                })
        ];
    }

    private static function getFormSchema(bool $isViewMode): array
    {
        return [
            Section::make()
                ->schema([
                    FileUpload::make('file_path')
                        ->label('Ảnh/tệp')
                        ->image()
                        ->disabled($isViewMode)
                        ->imagePreviewHeight('250')
                        ->columnSpanFull(),
                    TextInput::make('name')
                        ->label('Tiêu đề')
                        ->disabled($isViewMode),
                    TextInput::make('alt_text')
                        ->label('Văn bản thay thế')
                        ->disabled($isViewMode),
                    Textarea::make('description')
                        ->label('Mô tả')
                        ->disabled($isViewMode),
                ])
                ->columns(1),
            Section::make()
                ->schema([
                    TextInput::make('file_name')
                        ->label('Tên file')
                        ->disabled(),
                    TextInput::make('file_type')
                        ->label('Loại file')
                        ->disabled(),
                    TextInput::make('mime_type')
                        ->label('MIME Type')
                        ->disabled(),
                    TextInput::make('file_size')
                        ->label('Kích thước file')
                        ->disabled()
                        ->formatStateUsing(fn($state) => number_format($state / 1024, 2) . ' KB'),
                    Placeholder::make('created_at')
                        ->label('Ngày tạo')
                        ->content(fn($record) => $record->created_at ? $record->created_at->format('d/m/Y') : ''),
                    Placeholder::make('updated_at')
                        ->label('Ngày cập nhật')
                        ->content(fn($record) => $record->updated_at ? $record->updated_at->format('d/m/Y') : ''),
                ])
                ->columns(2)
        ];
    }
}
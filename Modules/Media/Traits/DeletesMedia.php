<?php

namespace Modules\Media\Traits;

use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteBulkAction;

trait DeletesMedia
{
    protected static function deleteMediaRecord($record)
    {
        if ($record->file_path && Storage::disk('public')->exists($record->file_path)) {
            Storage::disk('public')->delete($record->file_path);
        }
        $record->delete();
    }

    public static function getDeleteAction($label = 'Xóa')
    {
        return DeleteAction::make($label)
            ->action(function ($record) {
                self::deleteMediaRecord($record);
                Notification::make()
                    ->title('Đã xóa')
                    ->body('File đã được xóa khỏi hệ thống.')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation()
            ->modalDescription('Bạn có chắc chắn muốn xóa file này? Hành động này không thể hoàn tác.');
    }

    public static function getBulkDeleteAction()
    {
        return DeleteBulkAction::make()
            ->action(function ($records) {
                $records->each(function ($record) {
                    self::deleteMediaRecord($record);
                });
                Notification::make()
                    ->title('Đã xóa')
                    ->body('Các file đã chọn đã được xóa khỏi hệ thống.')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation()
            ->modalDescription('Bạn có chắc chắn muốn xóa các file đã chọn? Hành động này không thể hoàn tác.');
    }
}

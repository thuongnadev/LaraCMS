<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Tables\BulkActions;

use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Collection;

class PricingBulkAction
{
    public static function bulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
                BulkAction::make('Ẩn tất cả')
                    ->icon('heroicon-o-eye-slash')
                    ->color('primary')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update(['show' => false]);
                        });
                        Notification::make()
                            ->title('Ẩn thành công')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation(),
                BulkAction::make('Hiển thị tất cả')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update(['show' => true]);
                        });
                        Notification::make()
                            ->title('Hiện thành công')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation(),
            ]),
        ];
    }
}
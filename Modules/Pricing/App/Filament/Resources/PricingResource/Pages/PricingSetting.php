<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Pages;

use Archilex\ToggleIconColumn\Columns\ToggleIconColumn;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs as TabForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Modules\Pricing\App\Filament\Resources\PricingResource;
use Filament\Resources\Pages\Page;
use Modules\Pricing\Entities\PricingKey;

class PricingSetting extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithActions, InteractsWithTable, InteractsWithForms;

    protected static string $resource = PricingResource::class;
    protected static ?string $slug = 'cai-dat-bo-sung';
    protected static ?string $title = 'Quản lí từ khóa';
    protected static ?string $navigationLabel = 'Cài đặt từ khóa';
    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static string $view = 'pricing::filament.resources.pricing-resource.pages.pricing-setting';

    public function table(Table $table): Table
    {
        return $table
            ->query(PricingKey::query())
            ->columns([
                TextColumn::make('name')
                    ->label('Tên từ khóa')
                    ->searchable()
                    ->alignCenter()
                    ->weight(FontWeight::Bold)
                    ->sortable(),
                TextColumn::make('pricings_count')
                    ->label('Tổng số đang được sử dụng')
                    ->badge()
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('pricings.name')
                    ->label('Tên bảng giá')
                    ->searchable()
                    ->limit(50)
                    ->weight(FontWeight::Bold)
                    ->getStateUsing(function ($record) {
                        if ($record->pricings_count == 0) {
                            return 'Chưa có';
                        }

                        $pricingNames = $record->pricings->pluck('name')->filter()->unique();
                        $displayText = $pricingNames->join(', ');

                        return Str::limit($displayText, 30);
                    })
                    ->alignCenter(),

                ToggleIconColumn::make('status')
                    ->label('Trạng thái từ khóa')
                    ->tooltip(function ($record) {
                        return $record->status ? 'Hiển thị' : 'Ẩn';
                    })
                    ->onIcon('heroicon-o-eye')
                    ->offIcon('heroicon-o-eye-slash')
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->emptyStateHeading('Không có từ khóa')
            ->emptyStateIcon('heroicon-o-key')
            ->bulkActions(self::bulkActions())
            ->actions([
                ActionGroup::make([
                    ViewAction::make('Xem chi tiết')
                        ->label('Xem chi tiết')
                        ->icon('heroicon-o-eye')
                        ->infolist([
                            Grid::make(1)
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('Tên Từ khóa')
                                        ->weight(FontWeight::Bold)
                                        ->columnSpan(3),

                                    TextEntry::make('created_at')
                                        ->label('Ngày tạo')
                                        ->date('d/m/Y')
                                        ->columnSpan(3),

                                    IconEntry::make('status')
                                        ->label(fn($record) => $record->status ? 'Hiển thị' : 'Ẩn')
                                        ->trueIcon('heroicon-o-eye')
                                        ->falseIcon('heroicon-o-eye-slash')
                                        ->columnSpan(3),

                                    TextEntry::make('pricings_count')
                                        ->label('Tổng số đang được sử dụng')
                                        ->badge()
                                        ->columnSpan(3),

                                    TextEntry::make('pricings.name')
                                        ->label('Tên bảng giá')
                                        ->weight(FontWeight::Bold)
                                        ->getStateUsing(function ($record) {
                                            if ($record->pricings_count == 0) {
                                                return 'Chưa có';
                                            }

                                            $pricingNames = $record->pricings->pluck('name')->filter()->unique();
                                            $displayText = $pricingNames->join(', ');

                                            return $displayText;
                                        })
                                        ->tooltip(function ($record) {
                                            if ($record->pricings_count == 0) {
                                                return null;
                                            }
                                            return $record->pricings->pluck('name')->filter()->unique()->join(', ');
                                        })
                                        ->columnSpan(12),

                                ])->columns(12)
                        ])
                        ->iconSize(IconSize::Large)
                        ->color(Color::Blue)
                        ->modalHeading('Chi tiết từ khóa'),

                    EditAction::make('Cập nhật')
                        ->label('Cập nhật')
                        ->icon('heroicon-o-pencil-square')
                        ->color(Color::Amber)
                        ->iconSize(IconSize::Large)->modalHeading('Cập nhật từ khóa')
                        ->form([
                            TextInput::make('name')
                                ->label('Tên từ khóa')
                                ->rules('required|max:200')
                                ->unique(ignoreRecord: true),
                            Toggle::make('status')
                                ->label('Trạng thái từ khóa')
                                ->onIcon('heroicon-o-eye')
                                ->offIcon('heroicon-o-eye-slash')
                        ]),

                    DeleteAction::make('Xóa giá trị')
                        ->label('Xóa')
                        ->icon('heroicon-o-trash')
                        ->iconSize(IconSize::Large)->modalHeading('Xóa từ khóa'),
                ])
            ]);
    }


    public function getHeaderActions(): array
    {
        return [
            Action::make('Tạo bảng giá')
                ->url(CreatePricing::getUrl())
                ->button()
                ->color('info'),

            Actions\Action::make('Cập nhật trạng thái từ khóa')
                ->model(PricingKey::class)
                ->label('Cập nhật trạng thái từ khóa')
                ->icon('heroicon-o-key')
                ->form([
                    Fieldset::make('Thay đổi trạng thái của các Từ khóa')
                        ->schema(function () {
                            $keys = PricingKey::all('id', 'name', 'status');
                            $fields = [];
                            foreach ($keys as $key) {
                                $fields[] = Toggle::make("pricing_key_{$key->id}")
                                    ->label($key->name)
                                    ->onColor('success')
                                    ->onIcon('heroicon-o-eye')
                                    ->offIcon('heroicon-o-eye-slash')
                                    ->default($key->status);
                            }
                            return $fields;
                        })->columns(3),
                ])
                ->modalSubmitActionLabel('Lưu trạng thái')
                ->action(function (array $data) {
                    foreach ($data as $key => $value) {
                        if (strpos($key, 'pricing_key_') === 0) {
                            $pricingKeyId = str_replace('pricing_key_', '', $key);
                            PricingKey::where('id', $pricingKeyId)->update(['status' => $value]);
                        }
                    }
                    Notification::make()
                        ->title("Đã lưu trạng thái")
                        ->success()
                        ->send();
                })
                ->modalHeading('Cập nhật trạng thái từ khóa')
                ->slideOver(),


            Actions\Action::make('Xóa từ khóa')
                ->model(PricingKey::class)
                ->label('Xóa từ khóa')
                ->icon('heroicon-o-archive-box-x-mark')
                ->form([
                    CheckboxList::make('ids')
                        ->label('Danh sách từ khóa')
                        ->options(PricingKey::pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->validationMessages(['required' => 'Chọn 1 hoặc nhiều từ khóa mà bạn muốn xóa'])
                        ->noSearchResultsMessage('Không có dữ liệu')
                        ->searchPrompt('Tìm kiếm từ khóa....')
                        ->gridDirection('row')
                        ->searchDebounce(500)
                        ->bulkToggleable()
                        ->deselectAllAction(fn(FormAction $action) => $action->label('Bỏ chọn tất cả'))
                        ->selectAllAction(
                            fn(FormAction $action) => $action->label('Chọn tất cả'),
                        )->columns(3),
                ])
                ->modalSubmitActionLabel('Xóa')
                ->modalHeading('Xóa từ khóa')
                ->action(function (array $data) {

                    $count = PricingKey::whereIn('id', $data['ids'])->delete();
                    Notification::make()
                        ->title("Đã xóa {$count} từ khóa")
                        ->success()
                        ->send();
                    $this->dispatch('pricingKeysUpdated');
                })
                ->slideOver(),


            Actions\Action::make('Danh sách bảng giá')
                ->label('Quay lại Danh sách')
                ->icon('heroicon-o-arrow-left-end-on-rectangle')
                ->url(PricingResource::getUrl('index')),
        ];
    }

    public static function bulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make()->modalHeading('Xóa những từ khóa'),
                BulkAction::make('Ẩn tất cả')
                    ->icon('heroicon-o-eye-slash')
                    ->color('primary')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update(['status' => false]);
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
                            $record->update(['status' => true]);
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

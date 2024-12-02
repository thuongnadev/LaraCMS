<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Pages;

use Archilex\ToggleIconColumn\Columns\ToggleIconColumn;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
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
use Modules\Pricing\Entities\PricingValue;

class PricingSetting2 extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithActions, InteractsWithTable, InteractsWithForms;

    protected static string $resource = PricingResource::class;
    protected static ?string $slug = 'cai-dat-bo-sung';
    protected static ?string $title = 'Quản lí giá trị';

    protected static ?string $navigationLabel = 'Cài đặt từ khóa';
    protected static ?string $navigationIcon = 'heroicon-o-strikethrough';
    protected static string $view = 'pricing::filament.resources.pricing-resource.pages.pricing-setting2';

    public function table(Table $table): Table
    {
        return $table
            ->query(PricingValue::query())
            ->columns([
                TextColumn::make('content')
                    ->label('Tên giá trị')
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
                    ->label('Trạng thái giá trị')
                    ->tooltip(function ($record) {
                        return $record->status ? 'Hiển thị' : 'Ẩn';
                    })
                    ->onIcon('heroicon-o-eye')
                    ->offIcon('heroicon-o-eye-slash')
                    ->alignCenter(),

                ToggleIconColumn::make('is_check')
                    ->label('Trạng thái tích')
                    ->tooltip(function ($record) {
                        return $record->is_check ? 'Đã có' : 'Không có';
                    })
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->emptyStateHeading('Không có giá trị')
            ->emptyStateIcon('heroicon-o-strikethrough')
            ->bulkActions(self::bulkActions())
            ->actions([
                ActionGroup::make([
                    ViewAction::make('Xem chi tiết')
                        ->label('Xem chi tiết')
                        ->icon('heroicon-o-eye')
                        ->infolist([
                            Grid::make(1)
                                ->schema([
                                    TextEntry::make('content')
                                        ->label('Tên giá trị')
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

                                    IconEntry::make('is_check')
                                        ->label(fn($record) => $record->is_check ? 'Tích đã có' : 'Không có')
                                        ->trueIcon('heroicon-o-check')
                                        ->falseIcon('heroicon-o-x-mark')
                                        ->columnSpan(3),

                                    TextEntry::make('pricings_count')
                                        ->label('Tổng số đang được sử dụng')
                                        ->badge()
                                        ->columnSpan(12),

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
                        ->modalHeading('Chi tiết giá trị'),
                    EditAction::make('Cập nhật')
                        ->label('Cập nhật')
                        ->icon('heroicon-o-pencil-square')
                        ->color(Color::Amber)
                        ->iconSize(IconSize::Large)->modalHeading('Cập nhật giá trị')
                        ->form([
                            TextInput::make('content')
                                ->label('Tên giá trị')
                                ->rules('required|max:200')
                                ->unique(ignoreRecord: true),
                            Toggle::make('status')
                                ->label('Trạng thái giá trị')
                                ->onIcon('heroicon-o-eye')
                                ->offIcon('heroicon-o-eye-slash'),

                            Toggle::make('is_check')
                                ->label('Tích giá trị')
                                ->onIcon('heroicon-o-check')
                                ->offIcon('heroicon-o-x-mark')
                        ]),

                    DeleteAction::make('Xóa giá trị')
                        ->label('Xóa')
                        ->icon('heroicon-o-trash')
                        ->iconSize(IconSize::Large)->modalHeading('Xóa giá trị'),
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

            Actions\Action::make('Cập nhật trạng thái giá trị')
                ->model(PricingValue::class)
                ->label('Cập nhật tích giá trị')
                ->icon('heroicon-o-strikethrough')
                ->form([
                    Fieldset::make('Thay đổi trạng thái tích của các Giá trị')
                        ->schema(function () {
                            $values = PricingValue::all('id', 'content', 'is_check');
                            $fields = [];
                            foreach ($values as $value) {
                                $fields[] = Toggle::make("pricing_value_{$value->id}")
                                    ->label($value->content)
                                    ->onColor('success')
                                    ->onIcon('heroicon-o-check')
                                    ->offIcon('heroicon-o-x-mark')
                                    ->default($value->is_check);
                            }
                            return $fields;
                        })->columns(3),
                ])
                ->modalSubmitActionLabel('Lưu trạng thái')
                ->modalHeading('Cập nhật trạng thái giá trị')
                ->action(function (array $data) {
                    foreach ($data as $key => $value) {
                        if (strpos($key, 'pricing_value_') === 0) {
                            $pricingValueId = str_replace('pricing_value_', '', $key);
                            PricingValue::where('id', $pricingValueId)->update(['is_check' => $value]);
                        }
                    }
                    Notification::make()
                        ->title("Đã lưu trạng thái")
                        ->success()
                        ->send();
                })
                ->slideOver(),


            Actions\Action::make('Xóa giá trị')
                ->model(PricingValue::class)
                ->label('Xóa giá trị')
                ->icon('heroicon-o-archive-box')
                ->form([
                    CheckboxList::make('ids1')
                        ->label('Danh sách giá trị ')
                        ->options(PricingValue::pluck('content', 'id'))
                        ->searchable()
                        ->required()
                        ->validationMessages(['required' => 'Chọn 1 hoặc nhiều giá trị mà bạn muốn xóa'])
                        ->noSearchResultsMessage('Không có dữ liệu')
                        ->searchPrompt('Tìm kiếm giá trị....')
                        ->gridDirection('row')
                        ->searchDebounce(500)
                        ->bulkToggleable()
                        ->deselectAllAction(fn(FormAction $action) => $action->label('Bỏ chọn tất cả'))
                        ->selectAllAction(
                            fn(FormAction $action) => $action->label('Chọn tất cả'),
                        )->columns(3),
                ])
                ->modalSubmitActionLabel('Xóa')
                ->modalHeading('Xóa giá trị')
                ->action(function (array $data) {

                    $count = PricingValue::whereIn('id', $data['ids1'])->delete();
                    Notification::make()
                        ->title("Đã xóa {$count} giá trị")
                        ->success()
                        ->send();
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
                DeleteBulkAction::make()->modalHeading('Xóa những giá trị'),
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

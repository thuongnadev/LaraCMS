<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Pages;

use Archilex\ToggleIconColumn\Columns\ToggleIconColumn;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Components\ColorEntry;
use Filament\Forms\Components\Tabs as TabForm;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Contracts\HasActions;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Modules\Pricing\App\Filament\Resources\PricingResource;
use Filament\Resources\Pages\Page;
use Modules\Pricing\Entities\PricingContent;
use Thiktak\FilamentSimpleListEntry\Infolists\Components\SimpleListEntry;

class SettingPricing3 extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithActions, InteractsWithTable, InteractsWithForms;

    protected static string $resource = PricingResource::class;
    protected static ?string $slug = 'quan-ly-cau-hinh';
    protected static ?string $title = 'Quản lí cấu hình';
    protected static ?string $navigationLabel = 'Cài đặt từ khóa';
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static string $view = 'pricing::filament.resources.pricing-resource.pages.setting-pricing3';

    public function table(Table $table): Table
    {
        return $table
            ->query(PricingContent::query())
            ->columns([
                    TextColumn::make('name')
                        ->label('Tên cấu hình')
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
                    TextColumn::make('created_at')
                        ->label('Ngày tạo')
                        ->date('d/m/Y')
                        ->alignCenter()
                        ->sortable(),
                ]
            )
            ->bulkActions(self::bulkActions())
            ->emptyStateHeading('Không có cấu hình')
            ->emptyStateDescription('Hãy tạo cấu hình ở Nút "Tạo cấu hình hiển thị" trên! ')
            ->emptyStateIcon('heroicon-o-adjustments-horizontal')
            ->actions([
                ActionGroup::make([
                    ViewAction::make('Xem chi tiết')
                        ->label('Xem chi tiết')
                        ->icon('heroicon-o-eye')
                        ->color(Color::Blue)
                        ->infolist([
                            Tabs::make('Tabs')
                                ->tabs([
                                    Tabs\Tab::make('Định dạng hiển thị')
                                        ->icon('heroicon-m-adjustments-horizontal')
                                        ->schema([
                                            TextEntry::make('name')
                                                ->label('Tên cấu hình')
                                                ->columnSpan(6),

                                            ColorEntry::make('bg_color')
                                                ->label('Màu nền hàng')
                                                ->copyable()
                                                ->copyMessage('Đã sao chép!')
                                                ->copyMessageDuration(1500)
                                                ->columnSpan(6),

                                            ColorEntry::make('color_key')
                                                ->label('Màu chữ từ khóa')
                                                ->copyable()
                                                ->copyMessage('Đã sao chép!')
                                                ->copyMessageDuration(1500)
                                                ->columnSpan(6),

                                            ColorEntry::make('color_value')
                                                ->label('Màu chữ giá trị')
                                                ->copyable()
                                                ->copyMessage('Đã sao chép!')
                                                ->copyMessageDuration(1500)
                                                ->columnSpan(6),

                                            IconEntry::make('bold_key')
                                                ->label('')
                                                ->trueIcon('heroicon-o-bolt')
                                                ->alignCenter()
                                                ->falseIcon('heroicon-o-bolt-slash')
                                                ->tooltip(fn($record) => $record->bold_key ? 'Hiển thị Chữ in đậm từ khóa' : 'Ẩn Chữ in đậm từ khóa')
                                                ->columnSpan(1),

                                            IconEntry::make('bold_value')
                                                ->label('')
                                                ->trueIcon('heroicon-o-bolt')
                                                ->falseIcon('heroicon-o-bolt-slash')
                                                ->alignCenter()
                                                ->iconPosition(IconPosition::After)
                                                ->tooltip(fn($record) => $record->bold_value ? 'Hiển thị Chữ in đậm giá trị' : 'Ẩn Chữ in đậm giá trị')
                                                ->columnSpan(1),

                                            IconEntry::make('status')
                                                ->label('')
                                                ->tooltip(fn($record) => $record->status ? 'Hiển thị' : 'Ẩn')
                                                ->trueIcon('heroicon-o-eye')
                                                ->falseIcon('heroicon-o-eye-slash')
                                                ->columnSpan(1),

                                            TextEntry::make('pricings_count')
                                                ->label('Tổng số đang được sử dụng')
                                                ->badge()
                                                ->columnSpan(6),

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

                                        ])->columnSpan(12),
                                    Tabs\Tab::make('Cấu hình Tên Miền')
                                        ->icon('heroicon-m-server-stack')
                                        ->schema([
                                            SimpleListEntry::make('meta')
                                                ->label('Tên cấu hình')
                                                ->listStyle('list')
                                                ->state(function ($record) {
                                                    if (!$record->meta) {
                                                        return 'Không có dữ liệu';
                                                    }
                                                    if (!$record->status) {
                                                        return "Giá trị {$record->name} đã bị tắt";
                                                    }

                                                    $meta = $record->meta;

                                                    if (is_array($meta) && !empty($meta)) {
                                                        return implode(', ', $meta);
                                                    }

                                                    if (empty($meta)) {
                                                        return 'Không có giá trị';
                                                    }

                                                    return $meta;
                                                })
                                                ->badgeColor(function ($record) {
                                                    if (!$record->pricingContent || !$record->status) {
                                                        return 'danger';
                                                    }
                                                    return 'primary';
                                                })
                                                ->columnSpan(12),
                                        ])

                                ])->columns(12)
                        ])->iconSize(IconSize::Large)->modalHeading('Chi tiết cấu hình'),

                    EditAction::make('Cập nhật')
                        ->label('Cập nhật')
                        ->icon('heroicon-o-pencil-square')
                        ->color(Color::Amber)
                        ->iconSize(IconSize::Large)->modalHeading('Cập nhật cấu hình')
                        ->form([
                            TabForm::make('Tabs')
                                ->tabs([
                                    TabForm\Tab::make('Định dạng hiển thị')
                                        ->icon('heroicon-m-adjustments-horizontal')
                                        ->iconPosition(IconPosition::After)
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Tên cấu hình')
                                                ->placeholder('Nhập tên cấu hình...')
                                                ->rules('required|max:200')
                                                ->unique(ignoreRecord: true)
                                                ->columnSpan(6),
                                            ColorPicker::make('bg_color')
                                                ->label('Màu nền hàng')
                                                ->columnSpan(6),
                                            ColorPicker::make('color_key')
                                                ->label('Màu chữ từ khóa')
                                                ->columnSpan(6),
                                            ColorPicker::make('color_value')
                                                ->label('Màu chữ giá trị')
                                                ->columnSpan(6),
                                            Toggle::make('bold_key')
                                                ->label('Chữ in đậm từ khóa')
                                                ->onIcon('heroicon-o-bolt')
                                                ->offIcon('heroicon-o-bolt-slash')
                                                ->columnSpan(4),
                                            Toggle::make('bold_value')
                                                ->label('Chữ in đậm giá trị')
                                                ->onIcon('heroicon-o-bolt')
                                                ->offIcon('heroicon-o-bolt-slash')
                                                ->columnSpan(4),
                                        ])->columns(12),

                                    TabForm\Tab::make('Cấu hình cho dạng Tên Miền')
                                        ->icon('heroicon-m-folder-plus')
                                        ->iconPosition(IconPosition::After)
                                        ->schema([
                                            TextInput::make('metaAction1')
                                                ->label('')
                                                ->numeric()
                                                ->placeholder('Nhập số từ 1 đến 20')
                                                ->reactive()
                                                ->suffixAction(
                                                    FormAction::make('metaAction')
                                                        ->label('Thêm')
                                                        ->icon('heroicon-o-plus')
                                                        ->color(Color::Red)
                                                        ->action(function ($state, callable $set, callable $get) {
                                                            if (empty($state)) {
                                                                Notification::make()
                                                                    ->title('Lỗi')
                                                                    ->body('Vui lòng nhập số trước khi thêm.')
                                                                    ->danger()
                                                                    ->send();

                                                                return;
                                                            }

                                                            $quantity = is_numeric($state) ? (int)$state : 1;
                                                            $currentMeta = $get('meta') ?? '';
                                                            $currentCount = count($currentMeta);
                                                            $remainingSlots = 20 - $currentCount;

                                                            if ($quantity >= 1 && $quantity <= $remainingSlots) {
                                                                $newItems = array_fill(0, $quantity, [
                                                                    'nameMeta' => null,
                                                                ]);

                                                                $updatedListKey = array_merge($currentMeta, $newItems);
                                                                $set('meta', $updatedListKey);

                                                                Notification::make()
                                                                    ->title('Thêm thành công')
                                                                    ->success()
                                                                    ->send();
                                                            } elseif ($currentCount >= 20) {
                                                                Notification::make()
                                                                    ->title('Đã đạt giới hạn')
                                                                    ->body('Đã đạt tối đa 20 phần tử. Không thể thêm nữa.')
                                                                    ->warning()
                                                                    ->send();
                                                            } elseif ($quantity > $remainingSlots) {
                                                                Notification::make()
                                                                    ->title('Vượt quá giới hạn')
                                                                    ->body("Chỉ có thể thêm tối đa {$remainingSlots} phần tử nữa.")
                                                                    ->warning()->send();
                                                            } else {
                                                                Notification::make()
                                                                    ->title('Lỗi nhập số')
                                                                    ->body('Vui lòng nhập số trong khoảng từ 1 đến 20.')
                                                                    ->danger()
                                                                    ->send();
                                                            }
                                                        }),
                                                )
                                                ->columnSpan(12),

                                            Repeater::make('meta')
                                                ->label('Nội dung Tên Miền')
                                                ->simple(
                                                    TextInput::make('nameMeta')
                                                        ->label('Nội dung'),
                                                )
                                                ->collapsible()
                                                ->cloneable()
                                                ->defaultItems(0)
                                                ->reorderable(false)
                                                ->addable(false)
                                                ->addActionLabel('Thêm hàng')
                                                ->columnSpan(12)
                                        ])->columnSpan(12)
                                ]),
                        ]),

                    DeleteAction::make('Xóa cấu hình')
                        ->label('Xóa')
                        ->icon('heroicon-o-trash')
                        ->iconSize(IconSize::Large)->modalHeading('Xóa cấu hình'),
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

            Actions\CreateAction::make('Tạo cấu hình hiển thị')
                ->label('Tạo cấu hình hiển thị')
                ->model(PricingContent::class)
                ->icon('heroicon-o-plus')
                ->form([
                    TabForm::make('Tabs')
                        ->tabs([
                            TabForm\Tab::make('Định dạng hiển thị')
                                ->icon('heroicon-m-adjustments-horizontal')
                                ->iconPosition(IconPosition::After)
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Tên cấu hình')
                                        ->placeholder('Nhập tên cấu hình...')
                                        ->rules('required|max:100|min:3')
                                        ->unique(ignoreRecord: true)
                                        ->required()
                                        ->columnSpan(6),
                                    ColorPicker::make('bg_color')
                                        ->label('Màu nền hàng')
                                        ->columnSpan(6),
                                    ColorPicker::make('color_key')
                                        ->label('Màu chữ từ khóa')
                                        ->columnSpan(6),
                                    ColorPicker::make('color_value')
                                        ->label('Màu chữ giá trị')
                                        ->columnSpan(6),
                                    Toggle::make('bold_key')
                                        ->label('Chữ in đậm từ khóa')
                                        ->onIcon('heroicon-o-bolt')
                                        ->offIcon('heroicon-o-bolt-slash')
                                        ->columnSpan(4),
                                    Toggle::make('bold_value')
                                        ->label('Chữ in đậm giá trị')
                                        ->onIcon('heroicon-o-bolt')
                                        ->offIcon('heroicon-o-bolt-slash')
                                        ->columnSpan(4),
                                ])->columns(12),

                            TabForm\Tab::make('Cấu hình cho dạng Tên Miền')
                                ->icon('heroicon-m-folder-plus')
                                ->iconPosition(IconPosition::After)
                                ->schema([
                                    TextInput::make('metaAction1')
                                        ->label('')
                                        ->numeric()
                                        ->placeholder('Nhập số từ 1 đến 20')
                                        ->reactive()
                                        ->suffixAction(
                                            FormAction::make('metaAction')
                                                ->label('Thêm')
                                                ->icon('heroicon-o-plus')
                                                ->color(Color::Red)
                                                ->action(function ($state, callable $set, callable $get) {
                                                    if (empty($state)) {
                                                        Notification::make()
                                                            ->title('Lỗi')
                                                            ->body('Vui lòng nhập số trước khi thêm.')
                                                            ->danger()
                                                            ->send();

                                                        return;
                                                    }

                                                    $quantity = is_numeric($state) ? (int)$state : 1;
                                                    $currentMeta = $get('meta') ?? '';
                                                    $currentCount = count($currentMeta);
                                                    $remainingSlots = 20 - $currentCount;

                                                    if ($quantity >= 1 && $quantity <= $remainingSlots) {
                                                        $newItems = array_fill(0, $quantity, [
                                                            'nameMeta' => null,
                                                        ]);

                                                        $updatedListKey = array_merge($currentMeta, $newItems);
                                                        $set('meta', $updatedListKey);

                                                        Notification::make()
                                                            ->title('Thêm thành công')
                                                            ->success()
                                                            ->send();
                                                    } elseif ($currentCount >= 20) {
                                                        Notification::make()
                                                            ->title('Đã đạt giới hạn')
                                                            ->body('Đã đạt tối đa 20 phần tử. Không thể thêm nữa.')
                                                            ->warning()
                                                            ->send();
                                                    } elseif ($quantity > $remainingSlots) {
                                                        Notification::make()
                                                            ->title('Vượt quá giới hạn')
                                                            ->body("Chỉ có thể thêm tối đa {$remainingSlots} phần tử nữa.")
                                                            ->warning()->send();
                                                    } else {
                                                        Notification::make()
                                                            ->title('Lỗi nhập số')
                                                            ->body('Vui lòng nhập số trong khoảng từ 1 đến 20.')
                                                            ->danger()
                                                            ->send();
                                                    }
                                                }),
                                        )
                                        ->columnSpan(12),

                                    Repeater::make('meta')
                                        ->label('Nội dung Tên Miền')
                                        ->simple(
                                            TextInput::make('nameMeta')
                                                ->label('Nội dung'),
                                        )
                                        ->collapsible()
                                        ->cloneable()
                                        ->defaultItems(0)
                                        ->reorderable(false)
                                        ->addActionLabel('Thêm hàng')
                                        ->columnSpan(12)
                                ])->columnSpan(12)
                        ]),
                ])
                ->modalSubmitActionLabel('Lưu')
                ->modalHeading('Tạo mới cấu hình')
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
                DeleteBulkAction::make()->modalHeading('Xóa những cấu hình'),
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

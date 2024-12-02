<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Pages;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconPosition;
use Modules\Pricing\App\Filament\Resources\PricingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Pricing\Entities\PricingContent;
use Modules\Pricing\Entities\PricingKey;
use Modules\Pricing\Entities\PricingValue;

class EditPricing extends EditRecord
{
    protected static string $resource = PricingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Tạo từ khóa')
                ->model(PricingKey::class)
                ->icon('heroicon-o-plus')
                ->form([
                    TextInput::make('qtyKey')
                        ->label('')
                        ->numeric()
                        ->placeholder('Nhập số từ 1 đến 20')
                        ->reactive()
                        ->suffixAction(
                            Action::make('keyAction')
                                ->label('Thêm')
                                ->icon('heroicon-o-plus')
                                ->color(Color::Red)
                                ->action(function ($state, callable $set, callable $get) {
                                    // Kiểm tra nếu không nhập giá trị
                                    if (empty($state)) {
                                        Notification::make()
                                            ->title('Lỗi')
                                            ->body('Vui lòng nhập số trước khi thêm.')
                                            ->danger()
                                            ->send();

                                        return;
                                    }

                                    $quantity = is_numeric($state) ? (int) $state : 1;
                                    $currentListKey = $get('keywords') ?? [];
                                    $currentCount = count($currentListKey);
                                    $remainingSlots = 20 - $currentCount;

                                    if ($quantity >= 1 && $quantity <= $remainingSlots) {
                                        $newItems = array_fill(0, $quantity, [
                                            'name' => null,
                                            'status' => 1,
                                        ]);
                                        $updatedListKey = array_merge($currentListKey, $newItems);
                                        $set('keywords', $updatedListKey);
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

                    Repeater::make('keywords')
                        ->label('Danh sách nhóm bảng')
                        ->model(PricingKey::class)
                        ->schema([
                            TextInput::make('name')
                                ->label('Tên từ khóa')
                                ->placeholder('Nhập tên từ khóa..')
                                ->rules('required|min:3|max:100')
                                ->unique(ignoreRecord: true)
                                ->columnSpanFull(),
                            Hidden::make('status')
                                ->default(1)
                        ])
                        ->grid(3)
                        ->collapsed()
                        ->defaultItems(0)
                        ->addable(false)
                        ->reorderable(false)
                        ->cloneable()
                        ->columnSpan(12)
                ])
                ->modalSubmitActionLabel('Lưu')
                ->action(function (array $data) {

                    if (empty($data['keywords'])) {
                        Notification::make()
                            ->title('Lỗi')
                            ->body('Vui lòng thêm ít nhất một từ khóa trước khi lưu.')
                            ->danger()
                            ->send();

                        return;
                    }

                    foreach ($data['keywords'] as $keywordData) {
                        PricingKey::create([
                            'name' => $keywordData['name'],
                            'status' => $keywordData['status'],
                        ]);
                    }

                    Notification::make()
                        ->title('Đã lưu từ khóa')
                        ->success()
                        ->send();
                })
                ->modalHeading('Tạo mới từ khóa')
                ->slideOver(),

            Actions\Action::make('Tạo giá trị')
                ->model(PricingValue::class)
                ->icon('heroicon-o-plus')
                ->form([
                    TextInput::make('qtyValue')
                        ->label('')
                        ->numeric()
                        ->placeholder('Nhập số từ 1 đến 20')
                        ->reactive()
                        ->suffixAction(
                            Action::make('valueAction')
                                ->label('Thêm')
                                ->icon('heroicon-o-plus')
                                ->color(Color::Red)
                                ->action(function ($state, callable $set, callable $get) {
                                    // Kiểm tra nếu không nhập giá trị
                                    if (empty($state)) {
                                        Notification::make()
                                            ->title('Lỗi')
                                            ->body('Vui lòng nhập số trước khi thêm.')
                                            ->danger()
                                            ->send();

                                        return;
                                    }

                                    $quantity = is_numeric($state) ? (int) $state : 1;
                                    $currentListKey = $get('values1') ?? [];
                                    $currentCount = count($currentListKey);
                                    $remainingSlots = 20 - $currentCount;

                                    if ($quantity >= 1 && $quantity <= $remainingSlots) {
                                        $newItems = array_fill(0, $quantity, [
                                            'content' => null,
                                            'is_check' => 1,
                                            'status' => 1,
                                        ]);
                                        $updatedListKey = array_merge($currentListKey, $newItems);
                                        $set('values1', $updatedListKey);
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

                    Repeater::make('values1')
                        ->label('Danh sách nhóm bảng')
                        ->model(PricingValue::class)
                        ->schema([
                            TextInput::make('content')
                                ->label('Tên giá trị')
                                ->placeholder('Nhập tên giá trị..')
                                ->rules('required|min:1|max:100')
                                ->unique(ignoreRecord: true)
                                ->columnSpanFull(),

                            Toggle::make('is_check')
                                ->label('Tích giá trị')
                                ->onIcon('heroicon-o-check')
                                ->offIcon('heroicon-o-x-mark')
                                ->onColor('success')
                                ->offColor('danger')
                                ->default('true'),

                            Hidden::make('status')
                                ->default(1)
                        ])
                        ->grid(3)
                        ->collapsed()
                        ->defaultItems(0)
                        ->addable(false)
                        ->reorderable(false)
                        ->cloneable()
                        ->columnSpan(12)
                ])
                ->modalSubmitActionLabel('Lưu')
                ->action(function (array $data) {

                    if (empty($data['values1'])) {
                        Notification::make()
                            ->title('Lỗi')
                            ->body('Vui lòng thêm ít nhất một từ khóa trước khi lưu.')
                            ->danger()
                            ->send();

                        return;
                    }

                    foreach ($data['values1'] as $keywordData) {
                        PricingValue::create([
                            'content' => $keywordData['content'],
                            'is_check' => $keywordData['is_check'],
                            'status' => $keywordData['status'],
                        ]);
                    }

                    Notification::make()
                        ->title('Đã lưu giá trị')
                        ->success()
                        ->send();
                })
                ->modalHeading('Tạo mới giá trị')
                ->slideOver(),

            Actions\CreateAction::make('Tạo cấu hình hiển thị')
                ->label('Tạo cấu hình hiển thị')
                ->model(PricingContent::class)
                ->icon('heroicon-o-plus')
                ->form([
                    Tabs::make('Tabs')
                        ->tabs([
                            Tabs\Tab::make('Định dạng hiển thị')
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

                            Tabs\Tab::make('Cấu hình thêm')
                                ->icon('heroicon-m-folder-plus')
                                ->iconPosition(IconPosition::After)
                                ->schema([
                                    TextInput::make('metaAction1')
                                        ->label('')
                                        ->numeric()
                                        ->placeholder('Nhập số từ 1 đến 20')
                                        ->reactive()
                                        ->suffixAction(
                                            Action::make('metaAction')
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

                                                    $quantity = is_numeric($state) ? (int) $state : 1;
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

            Actions\Action::make('Quản lí nhóm bảng')
                ->model(PricingGroup::class)
                ->label('')
                ->icon('heroicon-o-wallet')
                ->tooltip('Quản lí nhóm bảng')
                ->extraAttributes(['style' =>  'display: inline-block;'])
                ->url(GroupSetting::getUrl()),

            Actions\Action::make('Quản lí loại bảng')
                ->model(PricingType::class)
                ->label('')
                ->icon('heroicon-o-ticket')
                ->tooltip('Quản lí loại bảng')
                ->extraAttributes(['style' =>  'display: inline-block;'])
                ->url(TypeSetting::getUrl()),

            Actions\Action::make('Quản lí từ khóa')
                ->model(PricingKey::class)
                ->label('')
                ->icon('heroicon-o-key')
                ->tooltip('Quản lí từ khóa')
                ->extraAttributes(['style' =>  'display: inline-block;'])
                ->url(PricingSetting::getUrl()),

            Actions\Action::make('Quản lí giá trị')
                ->model(PricingValue::class)
                ->label('')
                ->icon('heroicon-o-strikethrough')
                ->tooltip('Quản lí giá trị')
                ->extraAttributes(['style' =>  'display: inline-block;'])
                ->url(PricingSetting2::getUrl()),

            Actions\Action::make('Quản lí cấu hình')
                ->model(PricingValue::class)
                ->label('')
                ->icon('heroicon-o-adjustments-horizontal')
                ->tooltip('Quản lí cấu hình')
                ->extraAttributes(['style' =>  'display: inline-block;'])
                ->url(SettingPricing3::getUrl()),

            Actions\Action::make('Quay lại Danh sách')
                ->label('')
                ->icon('heroicon-o-arrow-left-end-on-rectangle')
                ->tooltip('Quay lại danh sách bảng giá')
                ->extraAttributes(['style' =>  'display: inline-block;'])
                ->url(PricingResource::getUrl('index')),

            Actions\DeleteAction::make('Xóa bảng giá')
                ->label('')
                ->icon('heroicon-o-trash')
                ->extraAttributes(['style' =>  'display: inline-block;'])
                ->tooltip('Xóa bảng giá'),


        ];
    }
}

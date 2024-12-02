<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Forms;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Pricing\Entities\PricingGroup;
use Modules\Pricing\Entities\PricingType;
use Modules\Pricing\Entities\PricingKey;
use Modules\Pricing\Entities\PricingKeyValue;
use Modules\Pricing\Entities\PricingValue;
use Modules\Pricing\Entities\PricingContent;

class PricingForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Nội dung Bảng giá')
                    ->tabs([
                        self::createPricingTab(),
                        self::createGroupTypeTab(),
                    ])->activeTab(1)->columnSpanFull(),
            ])->columns(12);
    }

    private static function createPricingTab(): Tabs\Tab
    {
        return Tabs\Tab::make('Tạo bảng giá')
            ->icon('heroicon-o-window')
            ->schema([
                self::createNameInput(),
                self::createGroupSelect(),
                self::createTypeSelect(),
                self::createToggle(),
                ...self::createDatePickers(),
                self::createQuantityInput(),
                self::deleteAllItem(),
                self::pricingContent(),
            ])->columns(12);
    }

    private static function pricingContent(): TableRepeater
    {
        return TableRepeater::make('pricingKeyValues')
            ->label('Nội dung bảng giá')
            ->relationship('pricingKeyValues', function ($query) {
                $query->where(function ($query) {
                    $query->whereNull('pricing_key_id')
                        ->orWhereHas('pricingKey', function ($query) {
                            $query->where('status', true);
                        });
                })->where(function ($query) {
                    $query->whereNull('pricing_value_id')
                        ->orWhereHas('pricingValue', function ($query) {
                            $query->where('status', true);
                        });
                })->where(function ($query) {
                    $query->whereNull('pricing_content_id')
                        ->orWhereHas('pricingContent', function ($query) {
                            $query->where('status', true);
                        });
                });
            })
            ->headers([
                Header::make('Từ khóa')->align(Alignment::Center)->width('150px')->markAsRequired(),
                Header::make('Giá trị')->align(Alignment::Center)->width('150px')->markAsRequired(),
                Header::make('Cấu hình')->align(Alignment::Center)->width('150px')->markAsRequired(),
            ])
            ->schema(function (callable $get) {
                $changeStyle = $get('changeStyle');
                return [
                    self::createKeySelect($changeStyle),
                    self::createValueSelect($changeStyle),
                    self::createContent($changeStyle),
                ];
            })
            ->columnSpan('full')
            ->stackAt(MaxWidth::Large)
            ->emptyLabel('Không có nội dung nào !')
            ->cloneable()
            ->collapsible()
            ->defaultItems(1)
            ->addActionLabel('Thêm nội dung')
            ->emptyLabel('Không có nội dung');
    }

    private static function createNameInput(): TextInput
    {
        return TextInput::make('name')
            ->label('Tên bảng')
            ->placeholder('Nhập tên bảng')
            ->prohibitedIf('pricingGroup', 'id')
            ->required()
            ->rules([
                'max:255',
                'min:3',
                function (Get $get) {
                    $pricingId = $get('id');
                    return $pricingId
                        ? Rule::unique('pricings', 'name')
                            ->where(function ($query) use ($get) {
                                $query->where('pricing_group_id', $get('pricing_group_id'));

                                if ($get('pricing_type_id')) {
                                    $query->where('pricing_type_id', $get('pricing_type_id'));
                                } else {
                                    $query->whereNotNull('pricing_type_id');
                                }
                                return $query;
                            })->ignore($pricingId, 'id')
                        : Rule::unique('pricings', 'name')
                            ->where(function ($query) use ($get) {
                                $query->where('pricing_group_id', $get('pricing_group_id'));

                                if ($get('pricing_type_id')) {
                                    $query->where('pricing_type_id', $get('pricing_type_id'));
                                } else {
                                    $query->whereNotNull('pricing_type_id');
                                }
                                return $query;
                            });
                },
            ])
            ->columnSpan(3);
    }

    private static function createGroupSelect(): Select
    {
        return Select::make('pricing_group_id')
            ->label('Chọn nhóm bảng')
            ->relationship('pricingGroup', 'name')
            ->required()
            ->createOptionForm([
                TextInput::make('name')
                    ->label("Tên nhóm bảng")
                    ->placeholder('Nhập tên nhóm bảng...')
                    ->rules('required|max:100|min:3')
                    ->unique(ignoreRecord: true)
                    ->required(),
            ])
            ->createOptionUsing(function (array $data): int {
                $model = PricingGroup::create([
                    'name' => $data['name']
                ]);
                return $model->id;
            })
            ->loadingMessage('Đang tải...')
            ->reactive()
            ->afterStateUpdated(fn(callable $set) => $set('pricing_type_id', null))
            ->preload()
            ->required()
            ->rules('required')
            ->searchable()
            ->columnSpan(4);
    }

    private static function createTypeSelect(): Select
    {
        return Select::make('pricing_type_id')
            ->label('Chọn loại bảng')
            ->relationship('pricingType', 'name', function ($query, callable $get) {
                $groupId = $get('pricing_group_id');
                if ($groupId) {
                    $query->where('pricing_group_id', $groupId);
                }
            })
            ->createOptionForm([
                TextInput::make('name')
                    ->label("Tên loại bảng")
                    ->placeholder('Nhập tên loại bảng...')
                    ->rules('required|max:100|min:3')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('pricing_group_id')
                    ->label("Chọn nhóm bảng")
                    ->relationship('pricingGroup', 'name')
                    ->searchable()
                    ->required()
                    ->validationMessages([
                        'required' => 'Vui lòng chọn nhóm bảng',
                    ])
                    ->preload()
            ])
            ->createOptionUsing(function (array $data): int {
                $model = PricingType::create([
                    'name' => $data['name'],
                    'pricing_group_id' => $data['pricing_group_id']
                ]);
                return $model->id;
            })
            ->loadingMessage('Đang tải...')
            ->reactive()
            ->required()
            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                if ($state) {
                    $type = PricingType::find($state);
                    if ($type) {
                        $set('pricing_group_id', $type->pricing_group_id);
                    }
                }
            })
            ->preload()
            ->searchable()
            ->columnSpan(4);
    }

    private static function createDatePickers()
    {
        return [
            DatePicker::make('created_at')
                ->label('Ngày tạo: ')
                ->native(false)
                ->default(now())
                ->timezone('Asia/Ho_Chi_Minh')
                ->columnSpan(3)
                ->disabled()
                ->hidden(fn(string $operation): bool => $operation === 'edit'),

            DatePicker::make('updated_at')
                ->label('Ngày cập nhật: ')
                ->native(false)
                ->default(now())
                ->disabled()
                ->timezone('Asia/Ho_Chi_Minh')
                ->columnSpan(4)
                ->hidden(fn(string $operation): bool => $operation === 'create'),
        ];
    }

    private static function createToggle(): Toggle
    {
        return Toggle::make('show')
            ->label('Ẩn - Hiện')
            ->inline(false)
            ->onIcon('heroicon-m-eye')
            ->offIcon('heroicon-m-eye-slash')
            ->onColor('success')
            ->default(true)
            ->columnSpan(1);
    }

    private static function createQuantityInput(): TextInput
    {
        return TextInput::make('quantity')
            ->label('Số lượng nội dung bảng')
            ->numeric()
            ->placeholder('Nhập số từ 1 đến 20')
            ->reactive()
            ->suffixAction(
                Action::make('updateQuantity')
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

                        $quantity = is_numeric($state) ? (int)$state : 1;
                        $currentPricingKeyValues = $get('pricingKeyValues') ?? [];
                        $currentCount = count($currentPricingKeyValues);
                        $remainingSlots = 20 - $currentCount;

                        if ($quantity >= 1 && $quantity <= $remainingSlots) {
                            $newItems = array_fill(0, $quantity, [
                                'pricing_key_id' => null,
                                'pricing_value_id' => null,
                                'pricing_content_id' => null,
                            ]);
                            $updatedPricingKeyValues = array_merge($currentPricingKeyValues, $newItems);
                            $set('pricingKeyValues', $updatedPricingKeyValues);
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
                                ->warning()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Lỗi nhập số')
                                ->body('Vui lòng nhập số trong khoảng từ 1 đến 20.')
                                ->danger()
                                ->send();
                        }
                    })
            )
            ->columnSpan(3);
    }

    private static function deleteAllItem(): Actions
    {
        return Actions::make([
            Action::make('deleteAllKeyValues')
                ->label('Xóa tất cả nội dung bảng')
                ->color(Color::Red)
                ->action(function (callable $set) {
                    $set('pricingKeyValues', []);
                    Notification::make()
                        ->title('Đã xóa tất cả nội dung bảng giá')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Xóa tất cả nội dung bảng giá')
                ->modalDescription('Bạn có chắc chắn muốn xóa tất cả nội dung bảng giá? Hành động này không thể hoàn tác.')
                ->modalSubmitActionLabel('Xóa')
                ->modalCancelActionLabel('Hủy')
                ->extraAttributes(['style' => 'margin-top: 32px;'])
                ->disabled(fn(callable $get): bool => empty($get('pricingKeyValues')))
        ])->columnSpan(3);
    }

    private static function createKeySelect($changeStyle): Select
    {
        return Select::make('pricing_key_id')
            ->label('Tên từ khóa')
            ->relationship('pricingKey', 'name', fn($query) => $query->where('status', true))
            ->createOptionForm([
                TextInput::make('name')
                    ->label('Tên từ khóa')
                    ->placeholder('Nhập từ khóa...')
                    ->rules('required|max:100|min:3')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->columnSpan(12),
            ])
            ->createOptionUsing(function (array $data, Select $component) {
                $model = PricingKey::create([
                    'name' => $data['name'],
                    'status' => 1
                ]);

                return $model->id;
            })
            ->getOptionLabelUsing(fn($value): ?string => PricingKey::find($value)?->name)
            ->getSearchResultsUsing(fn(string $search) => PricingKey::where('name', 'like', "%{$search}%")
                ->where('status', true)
                ->limit(50)
                ->pluck('name', 'id')
                ->toArray())
            ->loadingMessage('Đang tải...')
            ->searchable()
            ->required()
            ->preload()
            ->afterStateUpdated(function (Select $component, $state, callable $set) {
                if ($state) {
                    $component->state($state);
                }
            })
            ->columnSpan(12);
    }

    private static function createValueSelect($changeStyle): Select
    {
        return Select::make('pricing_value_id')
            ->label('Tên giá trị')
            ->relationship('pricingValue', 'content', fn($query) => $query->where('status', true))
            ->createOptionForm([
                TextInput::make('content')
                    ->label('Giá trị')
                    ->placeholder('Nhập giá trị...')
                    ->rules('required|max:100|min:3')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->columnSpan(12),

                Toggle::make('is_check')
                    ->label('Tích giá trị')
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark')
                    ->onColor('success')
                    ->default(true)
                    ->offColor('danger')
            ])
            ->createOptionUsing(function (array $data, Select $component) {
                $model = PricingValue::create([
                    'content' => $data['content'],
                    'is_check' => $data['is_check'],
                    'status' => 1
                ]);

                return $model->id;
            })
            ->getOptionLabelUsing(fn($value): ?string => PricingValue::find($value)?->content)
            ->getSearchResultsUsing(fn(string $search) => PricingValue::where('content', 'like', "%{$search}%")
                ->where('status', true)
                ->limit(50)
                ->pluck('content', 'id')
                ->toArray())
            ->loadingMessage('Đang tải...')
            ->searchable()
            ->preload()
            ->afterStateUpdated(function (Select $component, $state, callable $set) {
                if ($state) {
                    $component->state($state);
                }
            })
            ->columnSpan(12);
    }

    private static function createContent($changeStyle): Select
    {
        return Select::make('pricing_content_id')
            ->label('Cấu hình hàng')
            ->relationship('pricingContent', 'name', fn($query) => $query->where('status', true))
            ->createOptionForm([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Định dạng hiển thị')
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

                        Tabs\Tab::make('Cấu hình thêm')
                            ->icon('heroicon-m-folder-plus')
                            ->iconPosition(IconPosition::After)
                            ->schema([
                                Repeater::make('meta')
                                    ->label('Nội dung Tên Miền')
                                    ->simple(
                                        TextInput::make('nameMeta')
                                            ->label('Nội dung')
                                      )
                                    ->collapsible()
                                    ->cloneable()
                                    ->defaultItems(0)
                                    ->reorderable(false)
                                    ->addActionLabel('Thêm hàng')->columnSpan(12)
                            ])->columnSpan(12)
                    ])
            ])
            ->createOptionUsing(function (array $data, Select $component) {
                $model = PricingContent::create([
                    'name' => $data['name'],
                    'bg_color' => $data['bg_color'],
                    'color_key' => $data['color_key'],
                    'color_value' => $data['color_value'],
                    'bold_key' => $data['bold_key'],
                    'bold_value' => $data['bold_value'],
                    'meta' => $data['meta'],
                    'status' => 1
                ]);

                return $model->id;
            })
            ->getOptionLabelUsing(fn($value): ?string => PricingContent::find($value)?->name)
            ->getSearchResultsUsing(fn(string $search) => PricingContent::where('name', 'like', "%{$search}%")
                ->where('status', true)
                ->limit(50)
                ->pluck('name', 'id')
                ->toArray())
            ->loadingMessage('Đang tải...')
            ->searchable()
            ->preload()
            ->afterStateUpdated(function (Select $component, $state, callable $set) {
                if ($state) {
                    $component->state($state);
                }
            })
            ->columnSpan(12);
    }

    private static function createGroupTypeTab(): Tabs\Tab
    {
        return Tabs\Tab::make('Tạo nhóm & loại bảng')
            ->icon('heroicon-o-numbered-list')
            ->schema([
                self::createPriceTableToggleButtons(),
                self::createGroupGrid(),
                self::createTypeGrid(),
            ])
            ->columns(12);
    }

    private static function createPriceTableToggleButtons(): ToggleButtons
    {
        return ToggleButtons::make('price-table')
            ->label('')
            ->options([
                'group' => 'Nhóm bảng',
                'type' => 'Loại bảng',
            ])
            ->icons([
                'group' => 'heroicon-o-rectangle-group',
                'type' => 'heroicon-o-cube-transparent',
            ])
            ->colors([
                'group' => Color::Zinc,
                'type' => Color::Zinc,
            ])
            ->default('group')
            ->reactive()
            ->inline()
            ->columnSpanFull();
    }

    private static function createGroupGrid(): Grid
    {
        return Grid::make(1)->schema([
            self::createQuantityGroupInput(),
            self::createGroupActions(),
            self::createGroupRepeater(),
        ])->columns(12)->visible(fn($get) => in_array($get('price-table'), ['group']));
    }

    private static function createQuantityGroupInput(): TextInput
    {
        return TextInput::make('quantityGroup')
            ->label('')
            ->numeric()
            ->placeholder('Nhập số từ 1 đến 20')
            ->reactive()
            ->suffixAction(
                Action::make('groupAction')
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
                        $currentListGroup = $get('listGroup') ?? [];
                        $currentCount = count($currentListGroup);
                        $remainingSlots = 20 - $currentCount;

                        if ($quantity >= 1 && $quantity <= $remainingSlots) {
                            $newItems = array_fill(0, $quantity, [
                                'name' => null,
                            ]);
                            $updatedListGroup = array_merge($currentListGroup, $newItems);
                            $set('listGroup', $updatedListGroup);
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
                                ->warning()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Lỗi nhập số')
                                ->body('Vui lòng nhập số trong khoảng từ 1 đến 20.')
                                ->danger()
                                ->send();
                        }
                    })
            )
            ->columnSpan(3);
    }

    private static function createGroupActions(): Actions
    {
        return Actions::make([
            Action::make('Lưu tất cả')
                ->color(Color::Amber)
                ->action(function (callable $get, callable $set): void {
                    $listGroup = $get('listGroup');
                    $errors = [];
                    foreach ($listGroup as $index => $group) {
                        $validator = Validator::make($group, [
                            'name' => 'required|min:3|max:100|unique:pricing_groups,name',
                        ], [
                            'name.required' => 'Tên nhóm bảng không được để trống.',
                            'name.min' => 'Tên nhóm bảng phải có ít nhất 3 ký tự.',
                            'name.max' => 'Tên nhóm bảng không được quá 100 ký tự.',
                            'name.unique' => 'Tên nhóm bảng đã tồn tại.',
                        ]);

                        if ($validator->fails()) {
                            $errors[$index] = $validator->errors()->first('name');
                        }
                    }

                    if (!empty($errors)) {
                        foreach ($errors as $index => $error) {
                            Notification::make()
                                ->title("Lỗi tại nhóm bảng số " . ($index + 1))
                                ->body($error)
                                ->danger()
                                ->send();
                        }
                        return;
                    }

                    foreach ($listGroup as $group) {
                        if (!empty($group['name'])) {
                            PricingGroup::create([
                                'name' => $group['name'],
                            ]);
                        }
                    }

                    Notification::make()
                        ->title('Lưu tất cả thành công')
                        ->success()
                        ->send();
                })
                ->disabled(fn(callable $get): bool => empty($get('listGroup'))),
            Action::make('Xóa tất cả')
                ->color(Color::Red)
                ->action(function (callable $set) {
                    $set('listGroup', []);
                    Notification::make()
                        ->title('Đã xóa tất cả các mục')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Xóa tất cả các mục')
                ->modalDescription('Bạn có chắc chắn muốn xóa tất cả các mục? Hành động này không thể hoàn tác.')
                ->modalSubmitActionLabel('Xóa')
                ->modalCancelActionLabel('Hủy')
                ->disabled(fn(callable $get): bool => empty($get('listGroup'))),
        ])->columnSpan(4);
    }

    private static function createGroupRepeater(): Repeater
    {
        return Repeater::make('listGroup')
            ->label('Danh sách nhóm bảng')
            ->model(PricingGroup::class)
            ->schema([
                TextInput::make('name')
                    ->label('Tên nhóm bảng')
                    ->placeholder('Nhập tên nhóm bảng...')
                    ->columnSpanFull(),
            ])
            ->grid(4)
            ->collapsed()
            ->addable(false)
            ->reorderable(false)
            ->defaultItems(0)
            ->cloneable()
            ->columnSpan(12);
    }

    private static function createTypeGrid(): Grid
    {
        return Grid::make(1)->schema([
            self::createQuantityTypeInput(),
            self::createTypeActions(),
            self::createTypeRepeater(),
        ])->columns(12)->visible(fn($get) => in_array($get('price-table'), ['type']));
    }

    private static function createQuantityTypeInput(): TextInput
    {
        return TextInput::make('quantityType')
            ->label('')
            ->numeric()
            ->placeholder('Nhập số từ 1 đến 20')
            ->reactive()
            ->suffixAction(
                Action::make('typeAction')
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
                        $currentListType = $get('listType') ?? [];
                        $currentCount = count($currentListType);
                        $remainingSlots = 20 - $currentCount;

                        if ($quantity >= 1 && $quantity <= $remainingSlots) {
                            $newItems = array_fill(0, $quantity, [
                                'name' => null,
                                'pricing_group_id' => null,
                            ]);
                            $updatedListType = array_merge($currentListType, $newItems);
                            $set('listType', $updatedListType);
                            $set('quantityType', '');
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
                                ->warning()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Lỗi nhập số')
                                ->body('Vui lòng nhập số trong khoảng từ 1 đến 20.')
                                ->danger()
                                ->send();
                        }
                    })
            )
            ->columnSpan(3);
    }

    private static function createTypeActions(): Actions
    {
        return Actions::make([
            Action::make('Lưu tất cả')
                ->color(Color::Amber)
                ->action(function (callable $get, callable $set): void {
                    $listType = $get('listType');

                    if (empty($listType)) {
                        Notification::make()
                            ->title('Lỗi')
                            ->body('Danh sách loại bảng không được để trống.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $errors = [];
                    foreach ($listType as $index => $type) {
                        $validator = Validator::make($type, [
                            'name' => 'required|min:3|max:100|unique:pricing_types,name',
                            'pricing_group_id' => 'required'
                        ], [
                            'name.required' => 'Tên loại bảng không được để trống.',
                            'name.min' => 'Tên loại bảng phải có ít nhất 3 ký tự.',
                            'name.max' => 'Tên loại bảng không được quá 100 ký tự.',
                            'name.unique' => 'Tên loại bảng đã tồn tại.',
                            'pricing_group_id.required' => 'Nhóm bảng không được để trống'
                        ]);

                        if ($validator->fails()) {
                            $errors[$index] = $validator->errors()->first('name');
                        }
                    }

                    if (!empty($errors)) {
                        foreach ($errors as $index => $error) {
                            Notification::make()
                                ->title("Lỗi tại loại bảng số " . ($index + 1))
                                ->body($error)
                                ->danger()
                                ->send();
                        }
                        return;
                    }

                    foreach ($listType as $type) {
                        if (!empty($type['name']) && !empty($type['pricing_group_id'])) {
                            PricingType::create([
                                'name' => $type['name'],
                                'pricing_group_id' => $type['pricing_group_id'],
                            ]);
                        }
                    }

                    Notification::make()
                        ->title('Lưu tất cả thành công')
                        ->success()
                        ->send();
                })
                ->disabled(fn(callable $get): bool => empty($get('listType'))),
            Action::make('Xóa tất cả')
                ->color(Color::Red)
                ->action(function (callable $set) {
                    $set('listType', []);
                    Notification::make()
                        ->title('Đã xóa tất cả các mục')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Xóa tất cả các mục')
                ->modalDescription('Bạn có chắc chắn muốn xóa tất cả các mục? Hành động này không thể hoàn tác.')
                ->modalSubmitActionLabel('Xóa')
                ->modalCancelActionLabel('Hủy')
                ->disabled(fn(callable $get): bool => empty($get('listType'))),
        ])->columnSpan(4);
    }

    private static function createTypeRepeater(): Repeater
    {
        return Repeater::make('listType')
            ->label('Danh sách loại bảng')
            ->model(PricingType::class)
            ->schema([
                TextInput::make('name')
                    ->label('Tên loại bảng')
                    ->placeholder('Nhập tên loại bảng...')
                    ->columnSpanFull(),

                Select::make('pricing_group_id')
                    ->label('Chọn nhóm bảng giá')
                    ->relationship('PricingGroup', 'name')
                    ->preload()
                    ->searchable()
                    ->columnSpanFull()
            ])
            ->grid(4)
            ->collapsed()
            ->addable(false)
            ->reorderable(false)
            ->defaultItems(0)
            ->cloneable()
            ->columnSpan(12);
    }

}

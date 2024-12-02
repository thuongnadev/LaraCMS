<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Tabs as TabForm;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\ActionGroup;
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
use Illuminate\Support\Str;
use Modules\Pricing\App\Filament\Resources\PricingResource;
use Filament\Resources\Pages\Page;
use Modules\Pricing\Entities\PricingGroup;


class GroupSetting extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithActions, InteractsWithTable, InteractsWithForms;
    protected static ?string $slug = 'quan-ly-nhom-bang';
    protected static ?string $title = 'Quản lí nhóm bảng';
    protected static ?string $navigationLabel = 'Quản lí nhóm bảng';
    protected static string $resource = PricingResource::class;

    protected static string $view = 'pricing::filament.resources.pricing-resource.pages.group-setting';

    public function table(Table $table): Table
    {
        return $table
            ->query(PricingGroup::query())
            ->columns([
                    TextColumn::make('name')
                        ->label('Tên nhóm bảng')
                        ->searchable()
                        ->alignCenter()
                        ->weight(FontWeight::Bold)
                        ->sortable(),

                    TextColumn::make('pricings_count')
                        ->label('Tổng số đang được sử dụng')
                        ->badge()
                        ->alignCenter()
                        ->getStateUsing(fn (PricingGroup $record) => $record->pricings()->count()),

                    TextColumn::make('pricings.name')
                        ->label('Tên bảng giá')
                        ->searchable()
                        ->limit(50)
                        ->weight(FontWeight::Bold)
                        ->getStateUsing(function (PricingGroup $record) {
                            $pricingNames = $record->pricings()->pluck('name');
                            if ($pricingNames->isEmpty()) {
                                return 'Chưa có';
                            }
                            $valueNames = $pricingNames->implode(', ');

                            return Str::limit($valueNames, 30);
                        })
                        ->alignCenter(),

                    TextColumn::make('created_at')
                        ->label('Ngày tạo')
                        ->date('d/m/Y')
                        ->alignCenter()
                        ->sortable(),
                ]
            )
            ->bulkActions(self::bulkActions())
            ->emptyStateHeading('Không có nhóm bảng')
            ->emptyStateIcon('heroicon-o-key')
            ->actions([
                ActionGroup::make([
                    ViewAction::make('Xem chi tiết')
                        ->label('Xem chi tiết')
                        ->icon('heroicon-o-eye')
                        ->color(Color::Blue)
                        ->infolist([
                            Tabs::make('Tabs')
                                ->tabs([
                                    Tabs\Tab::make('Thông tin chính')
                                        ->icon('heroicon-m-adjustments-horizontal')
                                        ->schema([
                                            TextEntry::make('name')
                                                ->label('Tên nhóm bảng')
                                                ->columnSpan(6),

                                            TextEntry::make('pricings_count')
                                                ->label('Tổng số đang được sử dụng')
                                                ->badge()
                                                ->getStateUsing(fn (PricingGroup $record) => $record->pricings()->count())
                                                ->columnSpan(6),

                                            TextEntry::make('pricings.name')
                                                ->label('Tên bảng giá')
                                                ->weight(FontWeight::Bold)
                                                ->getStateUsing(function (PricingGroup $record) {
                                                    $pricingNames = $record->pricings()->pluck('name');
                                                    if ($pricingNames->isEmpty()) {
                                                        return 'Chưa có';
                                                    }
                                                    return $pricingNames->implode(', ');
                                                })
                                                ->columnSpan(12),

                                        ])->columnSpan(12),

                                ])->columns(12)
                        ])->iconSize(IconSize::Large)->modalHeading('Chi tiết nhóm bảng'),

                    EditAction::make('Cập nhật')
                        ->label('Cập nhật')
                        ->icon('heroicon-o-pencil-square')
                        ->color(Color::Amber)
                        ->iconSize(IconSize::Large)->modalHeading('Cập nhật nhóm bảng')
                        ->form([
                            TabForm::make('Tabs')
                                ->tabs([
                                    TabForm\Tab::make('Định dạng hiển thị')
                                        ->icon('heroicon-m-adjustments-horizontal')
                                        ->iconPosition(IconPosition::After)
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Tên nhóm bảng')
                                                ->placeholder('Nhập tên nhóm bảng...')
                                                ->rules('required|max:100|min:3')
                                                ->unique(ignoreRecord: true)
                                                ->columnSpan(12),
                                        ])->columns(12),
                                ]),
                        ]),

                    DeleteAction::make('Xóa nhóm bảng')
                        ->label('Xóa')
                        ->icon('heroicon-o-trash')
                        ->iconSize(IconSize::Large)->modalHeading('Xóa nhóm bảng')
                        ->requiresConfirmation()
                        ->modalHeading('Xóa nhóm bảng')
                        ->modalDescription('Bạn có chắc chắn xóa nhóm bảng này? Mọi dữ liệu của bảng giá và loại bảng liên quan sẽ mất hết và không khôi phục được!!!')
                        ->modalSubmitActionLabel('Vâng, hãy xóa nhóm bảng này'),
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

            Actions\CreateAction::make('Tạo nhóm bảng')
                ->label('Tạo nhóm bảng')
                ->model(PricingGroup::class)
                ->icon('heroicon-o-plus')
                ->form([
                    TabForm::make('Tabs')
                        ->tabs([
                            TabForm\Tab::make('Thông tin chính')
                                ->icon('heroicon-m-adjustments-horizontal')
                                ->iconPosition(IconPosition::After)
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Tên nhóm bảng')
                                        ->placeholder('Nhập tên nhóm bảng...')
                                        ->rules('required|max:100|min:3')
                                        ->unique(ignoreRecord: true)
                                        ->required()
                                        ->columnSpan(6),
                                ])->columns(12),
                        ]),
                ])
                ->modalSubmitActionLabel('Lưu')
                ->modalHeading('Tạo mới nhóm bảng')
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
                DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Xóa nhóm bảng')
                    ->modalDescription('Bạn có chắc chắn xóa những nhóm bảng này? Mọi dữ liệu của bảng giá và loại bảng liên quan sẽ mất hết và không khôi phục được!!!')
                    ->modalSubmitActionLabel('Vâng, hãy xóa những nhóm bảng này'),
            ]),
        ];
    }
}

<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Pages;

use Filament\Resources\Components\Tab;
use Modules\Pricing\App\Filament\Resources\PricingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Pricing\Entities\PricingKey;
use Modules\Pricing\Entities\PricingValue;
use Modules\Pricing\Entities\PricingGroup;
use Modules\Pricing\Entities\PricingType;
class ListPricing extends ListRecords
{
    protected static string $resource = PricingResource::class;

    protected function getHeaderActions(): array
    {
        return [
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

            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Tất cả'),

            'on' => Tab::make('Hiển thị')
                ->icon('heroicon-o-eye')
                ->query(fn ($query) => $query->where('show', true)),

            'off' => Tab::make('Ẩn')
                ->icon('heroicon-o-eye-slash')
                ->query(fn ($query) => $query->where('show', false)),
        ];
    }
}

<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Pages;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Modules\Pricing\App\Filament\Resources\PricingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Modules\Pricing\Entities\PricingKey;
use Modules\Pricing\Entities\PricingValue;
use Modules\Pricing\Entities\PricingType;
use Modules\Pricing\Entities\PricingGroup;

class PricingView extends ViewRecord
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

            Actions\EditAction::make('Cập nhật')
                ->label('')
                ->icon('heroicon-o-pencil-square')
                ->extraAttributes(['style' =>  'display: inline-block;'])
                ->tooltip('Chỉnh sửa bảng giá'),

            Actions\Action::make('Quay lại Danh sách')
                ->label('')
                ->icon('heroicon-o-arrow-left-end-on-rectangle')
                ->tooltip('Quay lại danh sách bảng giá')
                ->extraAttributes(['style' =>  'display: inline-block;'])
                ->url(PricingResource::getUrl('index')),



        ];
    }
}

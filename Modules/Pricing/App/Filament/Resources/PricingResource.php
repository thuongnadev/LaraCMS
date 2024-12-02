<?php

namespace Modules\Pricing\App\Filament\Resources;

use Filament\Infolists\Infolist;
use Modules\Pricing\App\Filament\Resources\PricingResource\Forms\PricingForm;
use Modules\Pricing\App\Filament\Resources\PricingResource\Tables\Actions\PricingInfolist;
use Modules\Pricing\App\Filament\Resources\PricingResource\Tables\PricingTable;
use Modules\Pricing\App\Filament\Resources\PricingResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Pricing\Entities\Pricing;

class PricingResource extends Resource
{
    protected static ?string $model = Pricing::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationGroup = 'Nội dung';

    public static function getNavigationLabel(): string
    {
        return 'Bảng giá';
    }

    public static function getModelLabel(): string
    {
        return 'Bảng giá';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Bảng giá';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return PricingForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return PricingTable::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return PricingInfolist::infolist($infolist);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPricing::route('/'),
            'quan-li-tu-khoa' => Pages\PricingSetting::route('/quan-li-tu-khoa'),
            'quan-li-gia-tri' => Pages\PricingSetting2::route('/quan-li-gia-tri'),
            'quan-li-cau-hinh' => Pages\SettingPricing3::route('/quan-li-cau-hinh'),
            'quan-li-nhom-bang' => Pages\GroupSetting::route('/quan-li-nhom-bang'),
            'quan-li-loại-bang' => Pages\TypeSetting::route('/quan-li-loai-bang'),
            'create' => Pages\CreatePricing::route('/create'),
            'view' => Pages\PricingView::route('/{record}'),
            'edit' => Pages\EditPricing::route('/{record}/edit'),
        ];
    }
}

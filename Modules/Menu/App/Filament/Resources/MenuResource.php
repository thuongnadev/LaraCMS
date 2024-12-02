<?php

declare(strict_types=1);

namespace Modules\Menu\App\Filament\Resources;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Menu\App\Filament\FilamentMenuBuilderPlugin;
use Modules\Menu\App\Filament\Resources\MenuResource\Pages\EditMenu;
use Modules\Menu\App\Filament\Resources\MenuResource\Pages\ListMenus;

class MenuResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Cấu hình web';

    public static function getModel(): string
    {
        return FilamentMenuBuilderPlugin::get()->getMenuModel();
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::count();
        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Grid::make(4)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('menu::menu-builder.resource.name.label'))
                            ->required()
                            ->columnSpan(3),

                        ToggleButtons::make('is_visible')
                            ->inline()
                            ->options([
                                true => __('menu::menu-builder.resource.is_visible.visible'),
                                false => __('menu::menu-builder.resource.is_visible.hidden'),
                            ])
                            ->colors([
                                true => 'primary',
                                false => 'danger',
                            ])
                            ->required()
                            ->label(__('menu::menu-builder.resource.is_visible.label'))
                            ->default(true)
                    ]),

                Group::make()
                    ->visible(fn (Component $component) => $component->evaluate(FilamentMenuBuilderPlugin::get()->getMenuFields()) !== [])
                    ->schema(FilamentMenuBuilderPlugin::get()->getMenuFields()),
            ]);
    }

    public static function table(Table $table): Table
    {
        $locations = FilamentMenuBuilderPlugin::get()->getLocations();

        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('menuItems'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('menu::menu-builder.resource.name.label')),
                Tables\Columns\TextColumn::make('locations.location')
                    ->label(__('menu::menu-builder.resource.locations.label'))
                    ->default(__('menu::menu-builder.resource.locations.empty'))
                    ->color(fn (string $state) => array_key_exists($state, $locations) ? 'primary' : 'gray')
                    ->formatStateUsing(fn (string $state) => $locations[$state] ?? $state)
                    ->limitList(2)
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('menu_items_count')
                    ->label(__('menu::menu-builder.resource.items.label'))
                    ->icon('heroicon-o-link')
                    ->numeric()
                    ->default(0)
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label(__('menu::menu-builder.resource.is_visible.label'))
                    ->sortable()
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}

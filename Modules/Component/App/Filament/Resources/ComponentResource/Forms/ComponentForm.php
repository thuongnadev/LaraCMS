<?php

namespace Modules\Component\App\Filament\Resources\ComponentResource\Forms;

use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Modules\Component\App\Enums\FieldInputType;
use Modules\Component\App\Enums\FieldType;
use Filament\Forms\Components\Toggle;

class ComponentForm
{
    public static function form(Form $form): Form
    {
        return $form->schema([
            self::mainSection()
        ]);
    }

    protected static function mainSection(): Section
    {
        return Section::make()
            ->schema([
                Grid::make(1)->columnSpan(2)->schema([self::componentFields(), self::configRepeater()]),
            ]);
    }

    protected static function componentFields(): TextInput
    {
        return TextInput::make('name')
            ->label('Tên thành phần')
            ->required()
            ->rules(['string', 'max:255']);
    }

    protected static function configRepeater(): Repeater
    {
        return Repeater::make('configurations')
            ->label('Cấu hình')
            ->relationship('configurations')
            ->schema([
                TextInput::make('name')
                    ->label('Tên cấu hình')
                    ->rule(['string', 'max:255'])
                    ->required(),

                TextInput::make('label')
                    ->label('Nhãn')
                    ->required(),

                Select::make('type')
                    ->label('Kiểu dữ liệu')
                    ->options(collect(FieldType::cases())->mapWithKeys(function ($case) {
                        return [$case->value => $case->name];
                    }))
                    ->required(),

                Select::make('type_field')
                    ->label('Loại trường')
                    ->options(collect(FieldInputType::cases())->mapWithKeys(function ($case) {
                        return [$case->value => $case->name];
                    }))
                    ->required(),

                TextInput::make('field_set')
                    ->label('Nhóm trường')
                    ->required(),

                Toggle::make('has_options')
                    ->label('Có tùy chọn')
                    ->reactive()
                    ->helperText('Bật nếu trường này có các tùy chọn'),

                Repeater::make('options')
                    ->label('Tùy chọn')
                    ->relationship('options')
                    ->schema([
                        TextInput::make('option_label')
                            ->label('Nhãn'),
                        TextInput::make('option_value')
                            ->label('Giá trị'),
                    ])
                    ->columns(2)
                    ->visible(fn($get) => $get('has_options'))
                    ->addActionLabel('Thêm tùy chọn')
                    ->columnSpan('full'),
            ])
            ->columns(2)
            ->reorderable();
    }
}

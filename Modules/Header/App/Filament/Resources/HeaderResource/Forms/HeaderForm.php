<?php

namespace Modules\Header\App\Filament\Resources\HeaderResource\Forms;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Toggle;

class HeaderForm
{
    public static function form(Form $form): Form
    {
        $dataList = [
            'email' => 'Email',
            'số điện thoại' => "Số điện thoại",
            'hotline' => 'Hotline',
            'zalo' => 'Zalo'
        ];

        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                ColorPicker::make('background_color')
                                    ->label('Chọn màu nền')
                                    ->helperText('Mặc định: #fff')
                            ])
                            ->columnSpan(6),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Tên header')
                                    ->required()
                                    ->placeholder('Nhập tên header...')
                                    ->columnSpan(12),
                                TextInput::make('logo_size')
                                    ->label('Kích thước logo')
                                    ->numeric()
                                    ->rules(['numeric', 'min:0'])
                                    ->required()
                                    ->minLength(0)
                                    ->placeholder('Nhập kích thước logo')
                                    ->helperText('* Kích thước logo: height (px)')
                                    ->columnSpan(12),
                                Toggle::make('status')
                                    ->label('Trạng thái')
                                    ->default(1)
                                    ->inline(true)
                                    ->helperText("Bật/Tắt trạng thái...")
                                    ->columnSpan(12)
                            ])
                            ->columnSpan(6)
                            ->columns(12),
                        Repeater::make('contacts')
                            ->label("Thông tin liên hệ")
                            ->relationship('contacts')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nhập tên liên hệ')
                                    ->required()
                                    ->columnSpan(6)
                                    ->placeholder('Nhập tên liên hệ...')
                                    ->datalist($dataList),
                                TextInput::make('value')
                                    ->label('Nhập giá trị liên hệ')
                                    ->required()
                                    ->placeholder('Nhập giá trị liên hệ...')
                                    ->columnSpan(6)
                            ])->columns(12)
                            ->columnSpan(12)
                            ->defaultItems(1)
                            ->addActionLabel('Thêm mới thông tin liên hệ')
                    ])->columns(12),
            ]);
    }
}

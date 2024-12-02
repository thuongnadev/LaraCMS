<?php

namespace Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Forms;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class ContactLinkForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('facebook_messenger_link')
                                    ->label('Facebook Messenger Link')
                                    ->placeholder('Nhập link Messenger')
                                    ->url()
                                    ->helperText('Không bắt buộc'),
                                TextInput::make('zalo_link')
                                    ->label('Zalo Link')
                                    ->placeholder('Nhập link Zalo')
                                    ->url()
                                    ->helperText('Không bắt buộc'),
                                TextInput::make('phone_number')
                                    ->label('Số điện thoại')
                                    ->placeholder('Nhập số điện thoại')
                                    ->tel()
                                    ->helperText('Không bắt buộc'),
                            ]),
                        Grid::make(3)
                            ->schema([
                                ColorPicker::make('text_color')
                                    ->label('Màu chữ')
                                    ->default('#000'),
                                Select::make('position')
                                    ->label('Vị trí nút')
                                    ->options([
                                        'bottom-left' => 'Dưới cùng bên trái',
                                        'bottom-right' => 'Dưới cùng bên phải',
                                    ])
                                    ->default('bottom-right'),
                                TextInput::make('bottom_offset')
                                    ->label('Khoảng cách từ bottom (px)')
                                    ->placeholder('Nhập khoảng cách tính bằng px')
                                    ->default(20)
                                    ->numeric(),
                            ]),
                    ])
            ]);
    }
}

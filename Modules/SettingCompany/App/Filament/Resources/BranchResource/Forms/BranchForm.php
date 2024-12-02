<?php

namespace Modules\SettingCompany\App\Filament\Resources\BranchResource\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Modules\SettingCompany\Entities\Business;

class BranchForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin công ty')
                    ->schema([
                        Select::make('business_id')
                            ->relationship('business', 'name')
                            ->default(function () {
                                return Business::first()->id;
                            })
                            ->label('công ty')
                            ->placeholder('Chọn công ty')
                            ->required()
                    ])
                    ->columns(1),
                Section::make('Thông tin chi nhánh')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên chi nhánh')
                            ->placeholder('Nhập tên chi nhánh...')
                            ->required()
                            ->rules(['string', 'max:255']),
                        TextInput::make('address')
                            ->label('Địa chỉ')
                            ->placeholder('Nhập địa chỉ...')
                            ->required()
                            ->rules(['string', 'max:255']),
                        TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->placeholder('Nhập số điện thoại...')
                            ->required()
                            ->rules(['string', 'regex:/^(\+?\d{1,3}[- ]?)?\d{10}$/']),
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Nhập email...')
                            ->required()
                            ->rules(['email']),
                        Select::make('status')
                            ->label('Trạng thái hoạt động')
                            ->options([
                                '1' => 'Hoạt động',
                                '0' => 'Ngừng hoạt động',
                            ])
                            ->required()
                    ])
                    ->columns(2),
            ]);
    }
}

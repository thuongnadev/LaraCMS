<?php

namespace Modules\SettingCompany\App\Filament\Resources\SettingCompanyResource\Forms;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class SettingCompanyForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin chung')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên doanh nghiệp')
                            ->placeholder('Nhập tên doanh nghiệp')
                            ->rules(['required', 'string', 'max:255']),
                        TextInput::make('address')
                            ->label('Địa chỉ')
                            ->placeholder('Nhập địa chỉ doanh nghiệp')
                            ->rules(['required', 'string', 'max:255']),
                        TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->placeholder('Nhập số điện thoại liên hệ')
                            ->rules(['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/']),
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Nhập địa chỉ email liên hệ')
                            ->rules(['required', 'email']),
                        TextInput::make('website')
                            ->label('Website')
                            ->placeholder('Nhập địa chỉ website (nếu có)')
                            ->nullable()
                            ->rules(['nullable', 'url']),
                    ])
                    ->columns(2),
                Section::make('Thông tin bổ sung')
                    ->schema([
                        TextInput::make('tax_code')
                            ->label('Mã số thuế')
                            ->placeholder('Nhập mã số thuế (nếu có)')
                            ->nullable()
                            ->rules(['nullable', 'string', 'max:50']),
                        Textarea::make('description')
                            ->label('Mô tả')
                            ->placeholder('Nhập mô tả doanh nghiệp (nếu có)')
                            ->nullable(),
                    ])
                    ->columns(1),
            ]);
    }
}

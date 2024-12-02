<?php

namespace Modules\Form\App\Filament\Resources\FormNotificationResource\Forms;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Modules\Form\Entities\Form as EntitiesForm;

class FormNotificationForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('form_id')
                            ->label('Chọn biểu mẫu')
                            ->options(EntitiesForm::pluck('name', 'id'))
                            ->required(),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('success_message')
                                    ->label('Thông báo thành công')
                                    ->default('Xin cảm ơn, thông tin của bạn đã được gửi thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất!')
                                    ->required(),
                                TextInput::make('error_message')
                                    ->label('Thông báo lỗi')
                                    ->default('Đã có lỗi xảy ra khi gửi form. Vui lòng thử lại sau.')
                                    ->required(),
                            ]),
                    ])
                    ->columns(1)
            ]);
    }
}

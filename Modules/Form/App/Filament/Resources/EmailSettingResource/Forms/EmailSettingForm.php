<?php

namespace Modules\Form\App\Filament\Resources\EmailSettingResource\Forms;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Modules\Form\Entities\Form as EntitiesForm;

class EmailSettingForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('form_id')
                                    ->label('Chọn Biểu mẫu cấu hình')
                                    ->options(function () {
                                        return EntitiesForm::pluck('name', 'id');
                                    })
                                    ->required(),
                                TextInput::make('to_email')
                                    ->label('Mail nhận')
                                    ->placeholder('Người nhận')
                                    ->required(),
                                TextInput::make('from_email')
                                    ->label('Mail gửi')
                                    ->placeholder('Người gửi')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('subject')
                                    ->label('Tiêu đề')
                                    ->placeholder('Nhập tiêu đề...')
                                    ->required(),

                                TextInput::make('additional_headers')
                                    ->label('Tiêu đề bổ sung')
                                    ->placeholder('Nhập tiêu đề bổ sung...')
                                    ->nullable(),
                            ]),

                        RichEditor::make('message_body')
                            ->label('Nội dung email')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'orderedList',
                                'unorderedList',
                                'h2',
                                'h3',
                                'paragraph',
                                'undo',
                                'redo',
                                'attachFiles',
                                'imageResize',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('email-attachments')
                            ->fileAttachmentsVisibility('public')
                            ->helperText('Tên trường của bạn. Ví dụ: {{ email }} {{ tel }}. (Lưu ý: Phải có khoảng cách giữa hai dấu ngoặc nhọn). Bạn có thể đính kèm và điều chỉnh kích thước ảnh bằng cách sử dụng các nút tương ứng.')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
            ]);
    }
}

<?php

namespace Modules\Form\App\Filament\Resources\FormResource\Forms;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class FormForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->heading('')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên biểu mẫu')
                            ->required()
                            ->placeholder('Nhập tên biểu mẫu...')
                            ->maxLength(255),
                        Section::make('Nội dung biểu mẫu')
                            ->schema([
                                Repeater::make('formFields')
                                    ->label('')
                                    ->relationship('formFields')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('type')
                                                    ->label('Loại Trường')
                                                    ->options([
                                                        'text' => 'Văn bản ngắn',
                                                        'email' => 'Email',
                                                        'textarea' => 'Văn bản dài',
                                                        'select' => 'Select',
                                                        'radio' => 'Tùy chọn radio',
                                                        'tel' => 'Số điện thoại',
                                                        'file' => 'File',
                                                    ])
                                                    ->required()
                                                    ->searchable(),
                                                TextInput::make('label')
                                                    ->label('Tên trường')
                                                    ->required()
                                                    ->placeholder('Nhập tiêu đề cho trường...')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                                        if (($get('slug') ?? '') !== Str::slug($old)) {
                                                            return;
                                                        }
                                                        $set('name', Str::slug($state));
                                                    }),
                                                TextInput::make('name')
                                                    ->label('Đường dẫn')
                                                    ->required()
                                                    ->placeholder('Tự động tạo từ tên trường...')
                                                    ->required(),
                                            ]),
                                        Textarea::make('options')
                                            ->label('Tùy chọn')
                                            ->helperText('Sử dụng cho các loại trường như select hoặc radio. Nhập các tùy chọn theo định dạng như: "A|B|C".')
                                            ->nullable()
                                            ->columnSpanFull()
                                            ->placeholder('A|B|C')
                                            ->visible(fn($get) => in_array($get('type'), ['select', 'radio'])),
                                        Grid::make(12)
                                            ->schema([
                                                Toggle::make('is_required')
                                                    ->label('Yêu cầu nhập')
                                                    ->default(false)
                                                    ->onColor('success')
                                                    ->columnSpan(4)
                                                    ->offColor('danger'),
                                                TextInput::make('min_length')
                                                    ->label('Độ dài tối thiểu')
                                                    ->numeric()
                                                    ->nullable()
                                                    ->helperText('Không bắt buộc')
                                                    ->columnSpan(4)
                                                    ->visible(fn($get) => in_array($get('type'), ['text', 'textarea'])),
                                                TextInput::make('max_length')
                                                    ->label('Độ dài tối đa')
                                                    ->numeric()
                                                    ->nullable()
                                                    ->helperText('Không bắt buộc')
                                                    ->columnSpan(4)
                                                    ->visible(fn($get) => in_array($get('type'), ['text', 'textarea'])),
                                            ]),
                                    ])
                                    ->columns(2)
                                    ->addable(true)
                                    ->deletable(true)
                                    ->reorderable(true)
                                    ->collapsible()
                                    ->orderColumn('sort_order')
                                    ->addActionLabel('Thêm trường mới')
                                    ->itemLabel(fn(array $state): ?string => $state['label'] ?? null),
                                Section::make([
                                    TextInput::make('submit_button_text')
                                        ->label('Nội dung nút Gửi')
                                        ->default('Gửi')
                                        ->required()
                                        ->maxLength(255),
                                ]),
                            ])
                            ->collapsible()
                    ])
                    ->columns(1)
            ]);
    }
}

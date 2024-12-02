<?php

namespace Modules\Footer\App\Filament\Resources\FooterResource\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Modules\Footer\App\Enum\ContentTypeEnum;
use Modules\Menu\Entities\Menu;
use Modules\SettingCompany\Entities\Business;
use Filament\Forms\Components\ColorPicker;

class FooterForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên footer')
                            ->required()
                            ->placeholder('Nhập tên footer...')
                            ->columnSpan(6),
                        Toggle::make('status')
                            ->label('Trạng thái')
                            ->default(1)
                            ->inline(false)
                            ->helperText("Bật/Tắt trạng thái...")
                            ->columnSpan(6),
                        Grid::make(3)
                            ->schema([
                                ColorPicker::make('title_color')
                                    ->label('Màu tiêu đề')
                                    ->nullable()
                                    ->helperText('Chọn màu cho tiêu đề của footer'),

                                ColorPicker::make('background_color')
                                    ->label('Màu nền')
                                    ->nullable()
                                    ->helperText('Chọn màu nền'),

                                ColorPicker::make('base_color')
                                    ->label('Màu cơ bản của footer')
                                    ->nullable()
                                    ->helperText('Chọn màu nền chung cho footer'),
                            ])
                            ->columnSpanFull(),
                        Repeater::make('columns')
                            ->relationship('columns')
                            ->label('Cột')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Tiêu đề cột')
                                            ->required(),
                                        Select::make('content_type')
                                            ->label('Loại nội dung')
                                            ->required()
                                            ->options(ContentTypeEnum::labels())
                                            ->rules(['in:' . implode(',', ContentTypeEnum::values())])
                                            ->reactive(),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        RichEditor::make('text_content')
                                            ->label('Nội dung văn bản')
                                            ->visible(fn($get) => $get('content_type') === 'text'),
                                        FileUpload::make('image_content')
                                            ->image()
                                            ->label('Hình ảnh')
                                            ->visible(fn($get) => $get('content_type') === 'image'),
                                        Textarea::make('iframe_content')
                                            ->rows(4)
                                            ->label('Mã iFrame')
                                            ->visible(fn($get) => $get('content_type') === 'iframe'),
                                        Repeater::make('socialMedia')
                                            ->relationship('socialMedia')
                                            ->label('Mạng xã hội')
                                            ->schema([
                                                Select::make('platform')
                                                    ->label('Nền tảng')
                                                    ->options([
                                                        'facebook' => 'Facebook',
                                                        'twitter' => 'Twitter',
                                                        'instagram' => 'Instagram',
                                                        'linkedin' => 'LinkedIn',
                                                        'youtube' => 'YouTube',
                                                    ])
                                                    ->reactive()
                                                    ->afterStateUpdated(fn($state, callable $set) => $set('icon', match ($state) {
                                                        'facebook' => 'fab fa-facebook-f',
                                                        'twitter' => 'fab fa-twitter',
                                                        'instagram' => 'fab fa-instagram',
                                                        'linkedin' => 'fab fa-linkedin-in',
                                                        'youtube' => 'fab fa-youtube',
                                                        default => null,
                                                    })),
                                                Hidden::make('icon'),
                                                TextInput::make('url')
                                                    ->label('URL')
                                                    ->url()
                                                    ->suffixIcon('heroicon-m-link'),
                                            ])
                                            ->itemLabel(fn (array $state): ?string => $state['platform'] ? ucfirst($state['platform']) : 'Nền tảng mới')
                                            ->collapsible()
                                            ->collapsed(true)
                                            ->columns(1)
                                            ->visible(fn($get) => $get('content_type') === 'social_media'),
                                        Textarea::make('google_map')
                                            ->label('Mã nhúng bản đồ Google')
                                            ->visible(fn($get) => $get('content_type') === 'google_map'),
                                        Select::make('menu_id')
                                            ->label('Chọn Menu')
                                            ->options(function () {
                                                return Menu::all()->pluck('name', 'id');
                                            })
                                            ->visible(fn($get) => $get('content_type') === 'menu'),
                                        Select::make('business_id')
                                            ->label('Chọn thông tin doanh nghiệp')
                                            ->options(function () {
                                                return Business::all()->pluck('name', 'id');
                                            })
                                            ->visible(fn($get) => $get('content_type') === 'business'),
                                    ])
                                    ->columns(1),
                            ])
                            ->grid(3)
                            ->columnSpan(12)
                            ->columns(12)
                            ->defaultItems(1)
                            ->orderColumn('order')
                            ->addActionLabel('Thêm cột')
                    ])
                    ->columns(12)
            ]);
    }
}

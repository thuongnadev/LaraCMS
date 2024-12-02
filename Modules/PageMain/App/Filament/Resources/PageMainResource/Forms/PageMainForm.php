<?php

namespace Modules\PageMain\App\Filament\Resources\PageMainResource\Forms;

use Filament\Forms\Components\{Card, DateTimePicker, FileUpload, RichEditor, Section, Select, Tabs, TextInput, Toggle};
use Filament\Forms\Form;

class PageMainForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Cài đặt trang')
                    ->tabs([
                        self::generalInfoTab(),
                        self::seoTab(),
                        self::advancedTab(),
                    ])->columnSpanFull(),
            ]);
    }

    private static function generalInfoTab(): Tabs\Tab
    {
        return Tabs\Tab::make('Thông tin chung')
            ->schema([
                TextInput::make('title')
                    ->label('Tiêu đề')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('content')
                    ->label('Nội dung')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Kích hoạt')
                    ->default(true),
                Card::make()
                    ->schema([
                        DateTimePicker::make('published_time')
                            ->label('Thời gian xuất bản'),
                        DateTimePicker::make('modified_time')
                            ->label('Thời gian chỉnh sửa'),
                    ])->columns(2),
            ]);
    }

    private static function seoTab(): Tabs\Tab
    {
        return Tabs\Tab::make('SEO')
            ->schema([
                self::metaInfoSection(),
                self::openGraphSection(),
                self::twitterSection(),
            ]);
    }

    private static function advancedTab(): Tabs\Tab
    {
        return Tabs\Tab::make('Nâng cao')
            ->schema([
                self::schemaSection(),
                self::sitemapSection(),
                TextInput::make('google_analytics_id')
                    ->label('ID Google Analytics')
                    ->maxLength(255),
                TextInput::make('author')
                    ->label('Tác giả')
                    ->maxLength(255),
                TextInput::make('section')
                    ->label('Chuyên mục')
                    ->maxLength(255),
                TextInput::make('tag')
                    ->label('Thẻ')
                    ->maxLength(255),
            ]);
    }

    private static function metaInfoSection(): Section
    {
        return Section::make('Thông tin Meta')
            ->schema([
                TextInput::make('meta_description')
                    ->label('Mô tả Meta')
                    ->maxLength(255),
                TextInput::make('meta_keywords')
                    ->label('Từ khóa Meta')
                    ->maxLength(255),
                TextInput::make('canonical_url')
                    ->label('URL chuẩn')
                    ->maxLength(255)
                    ->url(),
                TextInput::make('robots')
                    ->label('Robots')
                    ->default('index, follow')
                    ->maxLength(255),
            ])->columns(2);
    }

    private static function openGraphSection(): Section
    {
        return Section::make('Open Graph')
            ->schema([
                TextInput::make('og_title')
                    ->label('Tiêu đề OG')
                    ->maxLength(255),
                TextInput::make('og_description')
                    ->label('Mô tả OG')
                    ->maxLength(255),
                FileUpload::make('og_image')
                    ->label('Hình ảnh OG')
                    ->image(),
                Select::make('og_type')
                    ->label('Loại OG')
                    ->options([
                        'website' => 'Trang web',
                        'article' => 'Bài viết',
                        'product' => 'Sản phẩm',
                    ])
                    ->default('website'),
                TextInput::make('og_locale')
                    ->label('Ngôn ngữ OG')
                    ->default('vi_VN')
                    ->maxLength(255),
            ])->columns(2);
    }

    private static function twitterSection(): Section
    {
        return Section::make('Twitter')
            ->schema([
                Select::make('twitter_card')
                    ->label('Loại thẻ Twitter')
                    ->options([
                        'summary' => 'Tóm tắt',
                        'summary_large_image' => 'Tóm tắt với ảnh lớn',
                        'app' => 'Ứng dụng',
                        'player' => 'Trình phát',
                    ])
                    ->default('summary_large_image'),
                TextInput::make('twitter_title')
                    ->label('Tiêu đề Twitter')
                    ->maxLength(255),
                TextInput::make('twitter_description')
                    ->label('Mô tả Twitter')
                    ->maxLength(255),
                FileUpload::make('twitter_image')
                    ->label('Hình ảnh Twitter')
                    ->image(),
                TextInput::make('twitter_site')
                    ->label('Tài khoản Twitter của trang web')
                    ->maxLength(255),
                TextInput::make('twitter_creator')
                    ->label('Tài khoản Twitter của tác giả')
                    ->maxLength(255),
            ])->columns(2);
    }

    private static function schemaSection(): Section
    {
        return Section::make('Schema')
            ->schema([
                Select::make('schema_type')
                    ->label('Loại Schema')
                    ->options([
                        'WebPage' => 'Trang web',
                        'Article' => 'Bài viết',
                        'Product' => 'Sản phẩm',
                        'FAQPage' => 'Trang FAQ',
                    ])
                    ->default('WebPage'),
            ]);
    }

    private static function sitemapSection(): Section
    {
        return Section::make('Sitemap')
            ->schema([
                Select::make('sitemap_priority')
                    ->label('Độ ưu tiên Sitemap')
                    ->options([
                        '0.1' => '0.1',
                        '0.3' => '0.3',
                        '0.5' => '0.5',
                        '0.7' => '0.7',
                        '0.9' => '0.9',
                        '1.0' => '1.0',
                    ])
                    ->default('0.5'),
                Select::make('sitemap_frequency')
                    ->label('Tần suất cập nhật Sitemap')
                    ->options([
                        'always' => 'Luôn luôn',
                        'hourly' => 'Hàng giờ',
                        'daily' => 'Hàng ngày',
                        'weekly' => 'Hàng tuần',
                        'monthly' => 'Hàng tháng',
                        'yearly' => 'Hàng năm',
                        'never' => 'Không bao giờ',
                    ])
                    ->default('weekly'),
            ])->columns(2);
    }
}

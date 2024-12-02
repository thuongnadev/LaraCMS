<?php

namespace Modules\Post\App\Filament\Resources\PostResource\Forms;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Post\Entities\Post;
use Modules\Tag\Entities\Tag;
use Modules\Media\Traits\ImageUpload;

class PostForm
{
    use ImageUpload;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(['default' => 1, 'xl' => 6])
                    ->schema([
                        Grid::make()
                            ->schema([
                                Section::make()->schema(self::mainSectionFields()),
                            ])
                            ->columnSpan(['xl' => 4]),
                        Grid::make()
                            ->schema([
                                Section::make()->schema(self::seoSectionFields()),
                            ])
                            ->columnSpan(['xl' => 2]),
                    ]),
            ]);
    }

    private static function mainSectionFields(): array
    {
        return [
            self::titleField(),
            self::slugField(),
            self::summaryField(),
            self::contentField(),
        ];
    }

    private static function seoSectionFields(): array
    {
        return [
            self::postImageField(),
            self::seoTitleField(),
            self::seoDescriptionField(),
            self::seoKeywordsField(),
            self::publishedAtField(),
            self::tagsField(),
            self::categoriesField(),
            self::statusField(),
        ];
    }

    private static function titleField(): TextInput
    {
        return TextInput::make('title')
            ->label('Tiêu đề')
            ->placeholder('Nhập tiêu đề bài viết...')
            ->rules(['required', 'max:255'])
            ->required()
            ->live(onBlur: true)
            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                if (($get('slug') ?? '') !== Str::slug($old)) {
                    return;
                }
                $set('slug', Str::slug($state));
            })
            ->columnSpan(2);
    }

    private static function slugField(): TextInput
    {
        return TextInput::make('slug')
            ->label('Đường dẫn')
            ->placeholder('Tự động tạo từ tên bài viết...')
            ->required()
            ->rules(['required', function (Get $get) {
                $postId = $get('id');
                return $postId
                    ? Rule::unique('posts', 'slug')->ignore($postId)
                    : Rule::unique('posts', 'slug');
            }])
            ->columnSpan(2);
    }

    private static function summaryField(): Textarea
    {
        return Textarea::make('summary')
            ->label('Tóm tắt')
            ->maxLength(65535)
            ->rows(3)
            ->placeholder('Nhập tóm tắt cho bài viết...')
            ->columnSpan(4);
    }

    private static function contentField(): RichEditor
    {
        return RichEditor::make('content')
            ->label('Nội dung bài viết')
            ->required()
            ->columnSpan(4);
    }

    private static function tagsField(): TagsInput
    {
        return TagsInput::make('tags')
            ->label('Thẻ')
            ->placeholder('Chọn hoặc thêm thẻ...')
            ->suggestions(fn() => Tag::orderBy('created_at', 'desc')->limit(10)->pluck('name')->toArray())
            ->separator(',');
    }

    private static function categoriesField(): SelectTree
    {
        return SelectTree::make('categories')
            ->label('Danh mục')
            ->relationship(
                relationship: 'categories',
                titleAttribute: 'name',
                parentAttribute: 'parent_id',
                modifyQueryUsing: fn($query) => $query->where('category_type', 'post')
            )
            ->required()
            ->placeholder('Tìm kiếm...')
            ->enableBranchNode();
    }

    private static function postImageField(): mixed
    {
        return self::makeImageUpload('post_image', 'Ảnh chính', 'media', '100')
            ->required();
    }

    private static function seoTitleField(): TextInput
    {
        return TextInput::make('seo_title')
            ->label('SEO tiêu đề')
            ->id('post-seo-title')
            ->maxLength(60)
            ->rules(['max:60']);
    }

    private static function seoDescriptionField(): Textarea
    {
        return Textarea::make('seo_description')
            ->label('SEO mô tả')
            ->id('post-seo-description')
            ->maxLength(160)
            ->rules(['max:160']);
    }

    private static function seoKeywordsField(): TagsInput
    {
        return TagsInput::make('seo_keywords')
            ->label('Từ khóa')
            ->color('success')
            ->placeholder('Từ khóa cách nhau bởi dấu phẩy...')
            ->suggestions(
                Post::pluck('seo_keywords')
                    ->filter()
                    ->unique()
                    ->flatMap(fn($keywords) => explode(',', $keywords))
                    ->toArray()
            )
            ->rules(['max:255'])
            ->separator(',');
    }

    private static function publishedAtField(): DateTimePicker
    {
        return DateTimePicker::make('published_at')
            ->label('Ngày công khai')
            ->timezone('Asia/Ho_Chi_Minh')
            ->displayFormat('d/M/yyyy HH:mm a')
            ->rules(['nullable', 'date'])
            ->extraAttributes(['class' => 'mb-4']);
    }

    private static function statusField(): ToggleButtons
    {
        return ToggleButtons::make('status')
            ->label('Trạng thái')
            ->id('product-status')
            ->inline()
            ->default('draft')
            ->options([
                'draft' => 'Nháp',
                'published' => 'Xuất bản',
                'archived' => 'Lưu trữ',
            ])
            ->icons([
                'draft' => 'heroicon-o-pencil-square',
                'published' => 'heroicon-o-arrow-up-on-square-stack',
                'archived' => 'heroicon-o-archive-box',
            ])
            ->colors([
                'draft' => 'warning',
                'published' => 'success',
                'archived' => 'danger',
            ])
            ->reactive()
            ->afterStateUpdated(fn($state, callable $set) => $set('status', $state))
            ->extraAttributes([
                'wire:loading.class' => 'opacity-50 cursor-wait',
                'wire:target' => 'status',
                'class' => 'relative',
                'style' => 'position: relative;',
            ]);
    }
}

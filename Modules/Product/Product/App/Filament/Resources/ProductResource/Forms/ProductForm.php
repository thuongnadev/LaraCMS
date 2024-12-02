<?php

namespace Modules\Product\App\Filament\Resources\ProductResource\Forms;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Modules\Product\Entities\Sku;
use Modules\Product\Entities\ProductVariant;
use Modules\Product\Entities\ProductVariantOption;
use Illuminate\Support\Str;
use Modules\Product\Entities\Product;
use Illuminate\Validation\Rule;

class ProductForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(6)
                    ->schema([
                        Section::make('Thông tin sản phẩm')
                            ->schema([
                                ...self::createProductInfoFields(),
                                self::createSkuRepeater(),
                                ...self::createDescriptionFields(),
                                self::createVariantOptionsRepeater(),
                            ])
                            ->columnSpan(4),
                        Section::make('Thiết lập khác')
                            ->schema([
                                ...self::createImageFields(),
                                ...self::createCategoryAndTagFields(),
                                self::createSeoSection(),
                                ...self::createStatusAndVariant(),
                            ])
                            ->columnSpan(2),
                    ]),
            ]);
    }

    private static function createProductInfoFields(): array
    {
        return [
            Grid::make(6)
                ->schema([
                    TextInput::make('name')
                        ->label('Tiêu đề sản phẩm')
                        ->required()
                        ->id('product-name')
                        ->maxLength(255)
                        ->reactive()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                            if (($get('slug') ?? '') !== Str::slug($old)) {
                                return;
                            }
                            $set('slug', Str::slug($state));
                        })
                        ->columnSpan(3)
                        ->rules(['required', 'max:255']),
                    TextInput::make('slug')
                        ->label('Đường dẫn')
                        ->required()
                        ->id('product-slug')
                        ->maxLength(255)
                        ->reactive()
                        ->columnSpan(3)
                        ->rules([
                            'required',
                            'max:255',
                            function (Get $get) {
                                $productId = $get('id');
                                return $productId
                                    ? Rule::unique('products', 'slug')->ignore($productId)
                                    : Rule::unique('products', 'slug');
                            }
                        ]),
                ])
        ];
    }

    private static function createSkuRepeater(): Repeater
    {
        return Repeater::make('skus')
            ->label('SKU')
            ->schema([
                TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->rules([
                        'required',
                        function (Get $get) {
                            $productId = $get('../../id');
                            return $productId
                                ? Rule::unique('skus', 'sku')->ignore($productId, 'product_id')
                                : Rule::unique('skus', 'sku');
                        },
                    ]),
                TextInput::make('price')
                    ->label('Giá')
                    ->required()
                    ->numeric()
                    ->rules(['required', 'numeric', 'min:0']),
                TextInput::make('stock')
                    ->label('Tồn kho')
                    ->required()
                    ->numeric()
                    ->rules(['required', 'integer', 'min:0']),
            ])
            ->createItemButtonLabel('Thêm SKU mới')
            ->columns(3)
            ->minItems(1)
            ->required()
            ->saveRelationshipsUsing(function ($component, Product $record) {
                $skusData = $component->getState();
                foreach ($skusData as $skuData) {
                    Sku::updateOrCreate(
                        ['sku' => $skuData['sku'], 'product_id' => $record->id],
                        [
                            'price' => $skuData['price'],
                            'stock' => $skuData['stock'],
                        ]
                    );
                }
            });
    }

    private static function createDescriptionFields(): array
    {
        return [
            RichEditor::make('description')
                ->label('Mô tả ngắn')
                ->required()
                ->id('product-description')
                ->disableToolbarButtons(['codeBlock'])
                ->rules(['required']),
            RichEditor::make('content')
                ->label('Mô tả dài')
                ->id('product-content')
                ->disableToolbarButtons(['codeBlock']),
        ];
    }

    private static function createVariantOptionsRepeater(): Repeater
    {
        return Repeater::make('product_variant_options')
            ->label('Tùy chọn biến thể')
            ->schema([
                Select::make('variant_id')
                    ->label('Loại thuộc tính')
                    ->required()
                    ->id('product-variant-id')
                    ->options(ProductVariant::pluck('name', 'id')->toArray())
                    ->columnSpan(1)
                    ->rules(['required'])
                    ->suffixAction(
                        Action::make('add_variant')
                            ->label('')
                            ->icon('heroicon-o-plus')
                            ->form([
                                TextInput::make('name')
                                    ->label('Tên biến thể')
                                    ->rules(['required', 'unique:product_variants,name']),
                            ])
                            ->action(function (array $data): void {
                                ProductVariant::create(['name' => $data['name']]);
                                Notification::make()
                                    ->title('Thành công')
                                    ->body('Biến thể sản phẩm đã được thêm thành công!')
                                    ->success()
                                    ->send();
                            })
                    ),
                TextInput::make('name')
                    ->label('Tên tùy chọn')
                    ->required()
                    ->id('product-variant-option-name')
                    ->rules([
                        'required',
                        function (Get $get) {
                            $productId = $get('../../id');
                            $variantId = $get('variant_id');
                            $variantName = $get('name');

                            $existingIds = ProductVariantOption::query()
                                ->where('product_id', $productId)
                                ->where('variant_id', $variantId)
                                ->where('name', $variantName)
                                ->pluck('id')
                                ->toArray();

                                return Rule::unique('product_variant_options', 'name')
                                ->where(function ($query) use ($productId, $variantId) {
                                    return $query->where('product_id', $productId)
                                                 ->where('variant_id', $variantId);
                                })
                                ->whereNotIn('id', $existingIds);
                        }
                    ]),
                TextInput::make('value')
                    ->label('Giá trị tùy chọn')
                    ->required()
                    ->id('product-variant-option-value')
                    ->rules(['required']),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->id('product-variant-option-sku')
                    ->rules([
                        'required',
                        function (Get $get) {
                            $productId = $get('../../id');
                            return $productId
                                ? Rule::unique('skus', 'sku')->ignore($productId, 'product_id')
                                : Rule::unique('skus', 'sku');
                        },
                    ]),
                TextInput::make('price')
                    ->label('Giá')
                    ->required()
                    ->numeric()
                    ->id('product-variant-option-price')
                    ->rules(['required', 'numeric', 'min:0']),
                TextInput::make('stock')
                    ->label('Tồn kho')
                    ->required()
                    ->numeric()
                    ->id('product-variant-option-stock')
                    ->rules(['required', 'integer', 'min:0']),
                FileUpload::make('variant_image')
                    ->label('Ảnh biến thể')
                    ->id('product-variant-image')
                    ->image()
                    ->columnSpan(3)
                    ->rules(['image', 'max:2048']),
            ])
            ->createItemButtonLabel('Thêm tùy chọn mới')
            ->columns(2)
            ->visible(fn($get) => $get('has_variants') === 'yes')
            ->saveRelationshipsUsing(function ($component, Product $record) {
                $optionsData = $component->getState();
                $existingOptionIds = [];
                foreach ($optionsData as $optionData) {
                    $option = ProductVariantOption::updateOrCreate(
                        [
                            'product_id' => $record->id,
                            'variant_id' => $optionData['variant_id'],
                            'name' => $optionData['name'],
                        ],
                        [
                            'value' => $optionData['value'],
                        ]
                    );
                    $existingOptionIds[] = $option->id;
                    $sku = Sku::updateOrCreate(
                        [
                            'sku' => $optionData['sku'],
                            'product_id' => $record->id,
                        ],
                        [
                            'price' => $optionData['price'],
                            'stock' => $optionData['stock'],
                        ]
                    );
                    $sku->productVariantOptions()->sync([$option->id]);
                    // if (isset($optionData['variant_image'])) {
                    //     $option->clearMediaCollection('variant_images');
                    //     $option->addMedia($optionData['variant_image'])->toMediaCollection('variant_images');
                    // }
                }
                $record->productVariantOptions()
                    ->whereNotIn('id', $existingOptionIds)
                    ->delete();
            });
    }

    private static function createImageFields(): array
    {
        return [
            FileUpload::make('main_image')
                ->label('Ảnh chính')
                ->id('product-main-image')
                ->image()
                ->rules(['image', 'max:2048']),
            FileUpload::make('additional_images')
                ->label('Ảnh phụ')
                ->id('product-additional-images')
                ->image()
                ->multiple(),
        ];
    }

    private static function createCategoryAndTagFields(): array
    {
        return [
            SelectTree::make('categories')
                ->label('Danh mục')
                ->id('product-categories')
                ->relationship('categories', 'name', 'parent_id')
                ->placeholder('Chọn danh mục')
                ->enableBranchNode(),
            Select::make('tags')
                ->label('Thẻ tag')
                ->id('product-tags')
                ->options([
                    'new' => 'Mới',
                    'sale' => 'Giảm giá',
                    'popular' => 'Phổ biến',
                ])
                ->multiple()
                ->placeholder('Chọn thẻ tag'),
        ];
    }

    private static function createSeoSection(): Section
    {
        return Section::make('SEO')
            ->schema([
                TextInput::make('seo_title')
                    ->label('SEO tiêu đề')
                    ->id('product-seo-title')
                    ->maxLength(60)
                    ->rules(['max:60']),
                Textarea::make('seo_description')
                    ->label('SEO mô tả')
                    ->id('product-seo-description')
                    ->maxLength(160)
                    ->rules(['max:160']),
                TagsInput::make('seo_keywords')
                    ->label('Từ khóa')
                    ->color('success')
                    ->placeholder('Từ khóa cách nhau bởi dấu phẩy')
                    ->suggestions(
                        Product::pluck('seo_keywords')
                            ->filter()
                            ->unique()
                            ->flatMap(fn($keywords) => explode(',', $keywords))
                            ->toArray()
                    )
                    ->rules(['max:255'])
                    ->separator(','),
            ]);
    }

    private static function createStatusAndVariant(): array
    {
        return [
            ToggleButtons::make('status')
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
                    'style' => '
                        position: relative;
                    ',
                ])
                ->columnSpan(1),
            ToggleButtons::make('has_variants')
                ->label('Loại sản phẩm')
                ->id('product-has-variants')
                ->inline()
                ->default('no')
                ->options([
                    'no' => 'Sản phẩm bình thường',
                    'yes' => 'Sản phẩm có biến thể',
                ])
                ->colors([
                    'no' => 'warning',
                    'yes' => 'success',
                ])
                ->reactive()
                ->afterStateUpdated(fn($state, callable $set) => $set('has_variants', $state))
                ->extraAttributes([
                    'wire:loading.class' => 'opacity-50 cursor-wait',
                    'wire:target' => 'has_variants',
                    'class' => 'relative',
                    'style' => '
                        position: relative;
                    ',
                ])
                ->columnSpan(1),
        ];
    }
}

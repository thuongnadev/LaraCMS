<?php

namespace Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Forms;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Media\Traits\ImageUpload;

class ProductVpsForm
{
    use ImageUpload;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Tên sản phẩm')
                                    ->placeholder('Nhập tên sản phẩm...')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Tên sản phẩm không được vượt quá 255 ký tự.')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                        if (($get('slug') ?? '') !== Str::slug($old)) {
                                            return;
                                        }
                                        $set('slug', Str::slug($state));
                                    })
                                    ->rules([
                                        'required',
                                        'string',
                                        'max:255',
                                        Rule::unique('product_vps', 'name')->ignore($form->getRecord()?->id),
                                    ])
                                    ->validationMessages([
                                        'required' => 'Vui lòng nhập tên sản phẩm.',
                                        'string' => 'Tên sản phẩm phải là chuỗi ký tự.',
                                        'max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
                                        'unique' => 'Tên sản phẩm đã tồn tại.',
                                    ])
                                    ->columnSpan(1),

                                TextInput::make('slug')
                                    ->label('Đường dẫn')
                                    ->placeholder('Tự động tạo từ tên sản phẩm...')
                                    ->required()
                                    ->rules([
                                        'required',
                                        'string',
                                        'max:255',
                                        Rule::unique('product_vps', 'slug')->ignore($form->getRecord()?->id),
                                    ])
                                    ->validationMessages([
                                        'required' => 'Slug là bắt buộc.',
                                        'string' => 'Slug phải là chuỗi ký tự.',
                                        'max' => 'Slug không được vượt quá 255 ký tự.',
                                        'unique' => 'Slug đã tồn tại.',
                                    ])
                                    ->columnSpan(1),
                            ]),

                        Grid::make(2)
                            ->schema([
                                self::makeImageUpload('product_image', 'Ảnh chính', 'media', '100')
                                    ->required()
                                    ->columnSpan(1),
                                SelectTree::make('categories')
                                    ->label('Danh mục')
                                    ->relationship(
                                        relationship: 'categories',
                                        titleAttribute: 'name',
                                        parentAttribute: 'parent_id',
                                        modifyQueryUsing: fn($query) => $query->where('category_type', 'product')
                                    )
                                    ->placeholder('Tìm kiếm...')
                                    ->required()
                                    ->enableBranchNode()
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columnSpan(2),
            ])
            ->columns(2);
    }
}

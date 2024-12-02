<?php

namespace Modules\Category\App\Filament\Resources\CategoryResource\Forms;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use Illuminate\Validation\Rule;

class CategoryForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên danh mục')
                            ->placeholder('Nhập tên danh mục...')
                            ->rules(['required', 'max:255'])
                            ->validationMessages([
                                'required' => 'Vui lòng nhập tên danh mục',
                                'max' => 'Tên danh mục không được vượt quá 255 ký tự',
                            ])
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            })
                            ->columnSpan(1),
                        TextInput::make('slug')
                            ->label('Đường dẫn')
                            ->rules(['required', function (Get $get) {
                                $categoryId = $get('id');
                                return $categoryId
                                    ? Rule::unique('categories', 'slug')->ignore($categoryId)
                                    : Rule::unique('categories', 'slug');
                            }])
                            ->placeholder('Tự động tạo từ tên danh mục...')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Section::make()
                    ->schema([
                        SelectTree::make('parent_id')
                            ->label('Danh mục cha')
                            ->relationship('parent', 'name', 'parent_id')
                            ->enableBranchNode()
                            ->placeholder('Danh mục gốc...')
                            ->nullable(),
                        Select::make('category_type')
                            ->label('Loại danh mục')
                            ->rules(['required'])
                            ->options([
                                'product' => 'Sản phẩm',
                                'post' => 'Bài viết',
                            ])
                            ->placeholder('Chọn loại danh mục...'),
                        ToggleButtons::make('status')
                            ->label('Trạng thái')
                            ->inline()
                            ->default('1')
                            ->options([
                                '1' => 'Hiển thị',
                                '0' => 'Ẩn',
                            ])
                            ->icons([
                                '1' => 'heroicon-o-arrow-up-on-square-stack',
                                '0' => 'heroicon-o-archive-box',
                            ])
                            ->colors([
                                '1' => 'success',
                                '0' => 'danger',
                            ]),
                    ])
            ]);
    }
}

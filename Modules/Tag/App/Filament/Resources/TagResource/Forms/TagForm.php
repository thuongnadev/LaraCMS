<?php

namespace Modules\Tag\App\Filament\Resources\TagResource\Forms;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')->schema([
                    TextInput::make('name')
                        ->label('Tên thẻ')
                        ->placeholder('Nhập tên thẻ...')
                        ->rules(['max:255', function (Get $get) {
                            $postId = $get('id');
                            return $postId
                                ? Rule::unique('tags', 'name')->ignore($postId)
                                : Rule::unique('tags', 'name');
                        }])
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                            if (($get('slug') ?? '') !== Str::slug($old)) {
                                return;
                            }
                            $set('slug', Str::slug($state));
                        })
                        ->columnSpan(6),

                    TextInput::make('slug')
                        ->label('Đường dẫn')
                        ->placeholder('Tự động tạo từ tên thẻ...')
                        ->required()
                        ->rules([function (Get $get) {
                            $postId = $get('id');
                            return $postId
                                ? Rule::unique('tags', 'slug')->ignore($postId)
                                : Rule::unique('tags', 'slug');
                        }])
                        ->columnSpan(6)
                ])->columns(12)
            ]);
    }
}

<?php

namespace Modules\Process\App\Filament\Resources\ProcessResource\Forms;

use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
class ProcessForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->placeholder('Nhập tên quy trình...')
                            ->label('Tên quy trình'),
                        Textarea::make('description')
                            ->label('Mô tả')
                            ->placeholder('Nhập mô tả cho quy trình...'),
                        Repeater::make('steps')
                            ->relationship('steps')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Tên bước')
                                    ->placeholder('Nhập tên bước...')
                                    ->required(),
                                Textarea::make('description')
                                    ->label('Mô tả bước')
                                    ->placeholder('Nhập mô tả bước...')
                                    ->nullable(),
                                FileUpload::make('icon')
                                    ->image()
                                    ->required()
                            ])
                            ->grid(3)
                            ->label('Các bước')
                            ->collapsible()
                            ->orderColumn('order')
                            ->defaultItems(1)
                            ->addable('Thêm bước mới'),
                    ])
            ]);
    }
}

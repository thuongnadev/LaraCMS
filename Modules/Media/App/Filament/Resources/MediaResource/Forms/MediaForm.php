<?php

namespace Modules\Media\App\Filament\Resources\MediaResource\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;

class MediaForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tạo tệp media')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Tải file lên')
                            ->image()
                            ->imageEditor()
                            ->imagePreviewHeight('250')
                            ->openable()
                            ->downloadable()
                            ->multiple()
                            ->storeFileNamesIn('file_name')
                            ->required()
                            ->maxSize(5024)
                            ->acceptedFileTypes(['image/*'])
                            ->directory('media')
                            ->visibility('public')
                            ->maxFiles(10)
                            ->reorderable()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                if ($state) {
                                    $fileNames = collect($state)->map(function ($file) {
                                        return pathinfo($file, PATHINFO_BASENAME);
                                    })->toArray();
                                    $set('file_name', $fileNames);
                                }
                            })
                            ->validationMessages([
                                'required' => 'Chọn tệp để tải lên.',
                            ]),
                            Hidden::make('original_file_names'),
                        TextInput::make('name')
                            ->label('Tiêu đề')
                            ->placeholder('Nhập tiêu đề')
                            ->visible(fn($livewire) => $livewire->getRecord()),
                        TextInput::make('alt_text')
                            ->label('Văn bản thay thế')
                            ->placeholder('Nhập văn bản thay thế')
                            ->visible(fn($livewire) => $livewire->getRecord()),
                        TextInput::make('description')
                            ->label('Mô tả hình ảnh')
                            ->placeholder('Nhập mô tả hình ảnh')
                            ->visible(fn($livewire) => $livewire->getRecord())
                    ])
                    ->columnSpan(fn($livewire) => $livewire->getRecord() ? 3 : 4),

                Section::make('Thông tin tệp')
                    ->schema([

                        TextInput::make('file_type')
                            ->label('Loại file')
                            ->disabled(),
                        TextInput::make('mime_type')
                            ->label('MIME Type')
                            ->disabled(),
                        TextInput::make('file_size')
                            ->label('Kích thước file')
                            ->disabled()
                            ->formatStateUsing(function ($state) {
                                if (is_numeric($state)) {
                                    return number_format($state / 1024, 2) . ' KB';
                                }
                                return $state ?: 'N/A';
                            }),
                    ])
                    ->columnSpan([
                        'sm' => 3,
                        'md' => 1,
                    ])
                    ->hidden(fn($livewire) => !$livewire->getRecord()),
            ])
            ->columns(4);
    }
}

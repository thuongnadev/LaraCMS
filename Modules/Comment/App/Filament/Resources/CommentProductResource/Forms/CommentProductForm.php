<?php

namespace Modules\Comment\App\Filament\Resources\CommentProductResource\Forms;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Modules\Comment\Entities\Comment;
use Modules\Product\Entities\Product;

class CommentProductForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Thông tin cơ bản')
                            ->icon('heroicon-m-identification')
                            ->schema([
                                Select::make('account_id')
                                    ->label('Người bình luận: ')
                                    ->relationship('account', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->default(function () {
                                        $user = User::find(Auth::id());
                                        return $user ? $user->id : null;
                                    })
                                    ->rules('required|exists:users,id')
                                    ->columnSpan(6),
                                DatePicker::make('created_at')
                                    ->label('Ngày tạo: ')
                                    ->rules('date|before:tomorrow')
                                    ->default(now())
                                    ->columnSpan(3),
                                DatePicker::make('updated_at')
                                    ->label('Ngày cập nhật: ')
                                    ->rules('date|after_or_equal:created_at')
                                    ->columnSpan(3),
                                Select::make('commentable_id')
                                    ->label('Tên sản phẩm')
                                    ->preload()
                                    ->searchable()
                                    ->rules('required')
                                    ->options(function () {
                                        return Product::query()->pluck('name', 'id')->toArray();
                                    })->columnSpan(12),
                                Textarea::make('text')
                                    ->label('Nội dung')
                                    ->rule('required')
                                    ->columnSpan(12),
                            ])->columns(12),
                        Tabs\Tab::make('Hình ảnh bài viết')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                ToggleButtons::make('image_type')
                                    ->label('Loại hình ảnh')
                                    ->options([
                                        'all' => 'Cả hai',
                                        'url' => 'Chỉ thêm URLS ảnh',
                                        'file' => 'Chỉ thêm FILES ảnh'
                                    ])
                                    ->icons([
                                        'all' => 'heroicon-o-squares-2x2',
                                        'url' => 'heroicon-o-link',
                                        'file' => 'heroicon-o-photo',
                                    ])
                                    ->colors([
                                        'all' => Color::Orange,
                                        'url' => 'info',
                                        'file' => 'success',
                                    ])
                                    ->reactive()
                                    ->default('all')
                                    ->inline()
                                    ->columnSpanFull(),

                                Grid::make(1)->schema([
                                    Section::make('Thêm URLS hình ảnh')->schema([
                                        Repeater::make('urls')
                                            ->label('Danh sách URL hình ảnh')
                                            ->relationship()
                                            ->schema([
                                                TextInput::make('url')
                                                    ->label('Đường dẫn hình ảnh')
                                                    ->rules('required|url')
                                                    ->suffixIcon('heroicon-m-link')
                                                    ->columnSpanFull(),
                                            ])
                                            ->addActionLabel('Thêm đường dẫn hình ảnh')
                                            ->collapsible()
                                            ->cloneable()
                                            ->reorderable()->grid(2)
                                            ->columns(12)
                                    ])->columnSpanFull()->visible(fn($get) => in_array($get('image_type'), ['url', 'all'])),

                                    Section::make('Thêm FILES hình ảnh')->schema([
                                        Repeater::make('files')
                                            ->label('Danh sách tệp hình ảnh')
                                            ->relationship()
                                            ->schema([
                                                FileUpload::make('file')
                                                    ->label('Thêm hình ảnh')
                                                    ->directory('files')
                                                    ->imageEditor()
                                                    ->required()
                                                    ->columnSpanFull()
                                            ])
                                            ->addActionLabel('Thêm tệp hình ảnh')
                                            ->collapsible()
                                            ->reorderable()
                                            ->cloneable()
                                            ->grid(2)
                                            ->columns(12)
                                    ])->columnSpanFull()->visible(fn($get) => in_array($get('image_type'), ['file', 'all'])),
                                ])->columnSpanFull(),
                            ])->columnSpanFull(),
                        Tabs\Tab::make('Người phản hồi ')
                            ->icon('heroicon-m-user-group')
                            ->badgeColor(Color::Amber)
                            ->schema([
                                Repeater::make('replies')
                                    ->relationship()
                                    ->label('')
                                    ->schema([
                                        Select::make('account_id')
                                            ->label('Người phản hồi')
                                            ->relationship('account', 'name')
                                            ->disabled()
                                            ->columnSpan(12),
                                        Toggle::make('show')
                                            ->label('Ẩn / Hiện bình luận')
                                            ->onIcon('heroicon-m-eye')
                                            ->offIcon('heroicon-m-eye-slash')
                                            ->onColor('success')
                                            ->columnSpan(3),
                                        Toggle::make('flag')
                                            ->label('Báo cáo')
                                            ->onIcon('heroicon-m-flag')
                                            ->offIcon('heroicon-m-bolt-slash')
                                            ->onColor('warning')
                                            ->columnSpan(2),
                                        Toggle::make('like')
                                            ->label('Thích')
                                            ->onIcon('heroicon-m-hand-thumb-up')
                                            ->offIcon('heroicon-m-bolt-slash')
                                            ->onColor('info')
                                            ->columnSpan(2),
                                        Toggle::make('dislike')
                                            ->label('Không thích')
                                            ->onIcon('heroicon-m-hand-thumb-down')
                                            ->offIcon('heroicon-m-bolt-slash')
                                            ->onColor('danger')
                                            ->columnSpan(2),
                                        Toggle::make('pin')
                                            ->label('Ghim bình luận')
                                            ->onIcon('heroicon-m-bookmark')
                                            ->offIcon('heroicon-m-bookmark-slash')
                                            ->onColor(Color::Fuchsia)
                                            ->columnSpan(3),
                                    ])
                                    ->reorderable()
                                    ->collapsed()
                                    ->addable(false)
                                    ->columnSpanFull(),
                            ])->columnSpanFull()
                            ->hidden(fn (string $operation): bool => $operation === 'create'),
                        Tabs\Tab::make('Danh sách báo cáo')
                            ->icon('heroicon-m-flag')
                            ->badgeColor(Color::Amber)
                            ->schema([
                                Repeater::make('reports')
                                    ->relationship()
                                    ->label('')
                                    ->schema([
                                        Select::make('account_id')
                                            ->label('Người phản hồi')
                                            ->relationship('account', 'name')
                                            ->disabled()
                                            ->columnSpan(12),
                                        Textarea::make('content')
                                            ->label('Nội dung báo cáo')
                                            ->columnSpan(12),
                                    ])
                                    ->reorderable()
                                    ->collapsed()
                                    ->addable(false)
                                    ->columnSpanFull(),
                            ])->columnSpanFull()
                            ->hidden(function (?Comment $record, string $operation): bool {
                                if ($operation === 'create') {
                                    return true;
                                }

                                if ($record === null) {
                                    return true;
                                }

                                return $record->flag === false;
                            }),
                        Tabs\Tab::make('Cài đặt hiển thị')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Section::make('Trạng thái')->schema([
                                    Toggle::make('show')
                                        ->label('Ẩn / Hiện bình luận')
                                        ->onIcon('heroicon-m-eye')
                                        ->offIcon('heroicon-m-eye-slash')
                                        ->onColor('success')
                                        ->columnSpan(3),
                                    Toggle::make('flag')
                                        ->label('Báo cáo')
                                        ->onIcon('heroicon-m-flag')
                                        ->offIcon('heroicon-m-bolt-slash')
                                        ->onColor('warning')
                                        ->columnSpan(2),
                                    Toggle::make('like')
                                        ->label('Thích')
                                        ->onIcon('heroicon-m-hand-thumb-up')
                                        ->offIcon('heroicon-m-bolt-slash')
                                        ->onColor('info')
                                        ->columnSpan(2),
                                    Toggle::make('dislike')
                                        ->label('Không thích')
                                        ->onIcon('heroicon-m-hand-thumb-down')
                                        ->offIcon('heroicon-m-bolt-slash')
                                        ->onColor('danger')
                                        ->columnSpan(2),
                                    Toggle::make('pin')
                                        ->label('Ghim bình luận')
                                        ->onIcon('heroicon-m-bookmark')
                                        ->offIcon('heroicon-m-bookmark-slash')
                                        ->onColor(Color::Fuchsia)
                                        ->columnSpan(3),
                                ])->columns(12)
                            ])->columnSpanFull(),
                    ])->columnSpanFull(),
                Hidden::make('commentable_type')
                    ->label('')
                    ->default('Modules\Product\Entities\Product')
                    ->columnSpan(12),
            ])->columns(12);
    }
}

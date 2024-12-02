<?php

namespace Modules\Comment\App\Filament\Resources\CommentPostResource\Tables\Actions;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Auth;
use Modules\Comment\Entities\CommentFile;
use Modules\Comment\Entities\CommentUrl;
use Modules\Comment\Entities\CommentReply;

class CommentPostAction
{
    public static function action()
    {
        return [
            ActionGroup::make([
                ViewAction::make()->label('Xem chi tiết')->color('success'),
                EditAction::make()->label('Cập nhật')->color('warning'),
                Action::make('Phản hồi')
                    ->label('Phản hồi')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->model(CommentReply::class)
                    ->color('info')
                    ->form([
                        TextInput::make('account')
                            ->label('Phản hồi đến')
                            ->disabled()
                            ->default(fn($record) => $record->account->name)
                            ->rules('required|exists:users,name')
                            ->columnSpanFull(),
                        Section::make([
                            Toggle::make('show')
                                ->label('Trạng thái (Ẩn / Hiện)')
                                ->onIcon('heroicon-m-eye')
                                ->offIcon('heroicon-m-eye-slash')
                                ->onColor('success')
                                ->columnSpan(4),

                            Toggle::make('pin')
                                ->label('Ghim bình luận')
                                ->onIcon('heroicon-m-bookmark')
                                ->offIcon('heroicon-m-bookmark-slash')
                                ->onColor(Color::Fuchsia)
                                ->columnSpan(4),

                            Toggle::make('flag')
                                ->label('Báo cáo')
                                ->onIcon('heroicon-m-flag')
                                ->offIcon('heroicon-m-bolt-slash')
                                ->onColor('warning')
                                ->columnSpan(4),

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
                                ->columnSpan(4),

                            Textarea::make('text')
                                ->label('Nội dung')
                                ->rules('required')
                                ->columnSpan(12),

                            Hidden::make('account_id')
                                ->label('')
                                ->default(function () {
                                    $user = User::find(Auth::id());
                                    return $user ? $user->id : 'Không xác định';
                                })->columnSpan(4),

                            Hidden::make('comment_id')
                                ->default(fn($record) => $record->id)
                                ->columnSpan(4),
                        ])->columns(12),

                        ToggleButtons::make('image_type')
                            ->label('Loại hình ảnh')
                            ->options([
                                'all' => 'Tất cả',
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
                                Repeater::make('media_urls')
                                    ->label('Danh sách URL hình ảnh')
                                    ->schema([
                                        Hidden::make('comment_reply_id')
                                            ->default(fn($record) => $record->id)
                                            ->columnSpan(4),

                                        TextInput::make('url')
                                            ->label('Đường dẫn hình ảnh')
                                            ->suffixIcon('heroicon-m-link')
                                            ->rules('required|url')
                                            ->columnSpanFull(),
                                    ])
                                    ->addActionLabel('Thêm đường dẫn hình ảnh')
                                    ->collapsible()
                                    ->cloneable()
                                    ->reorderable()
                                    ->grid(2)
                                    ->columns(12)
                            ])->columnSpanFull()->visible(fn($get) => in_array($get('image_type'), ['url', 'all'])),

                            Section::make('Thêm FILES hình ảnh')->schema([
                                Repeater::make('media_files')
                                    ->label('Danh sách tệp hình ảnh')
                                    ->schema([
                                        Hidden::make('comment_reply_id')
                                            ->default(fn($record) => $record->id)
                                            ->columnSpan(4),
                                        FileUpload::make('file')
                                            ->label('Thêm hình ảnh')
                                            ->directory('files')
                                            ->required()
                                            ->imageEditor()
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
                    ])
                    ->action(function ($data, $record) {
                        // Lưu phản hồi bình luận
                        $commentReply = CommentReply::create([
                            'text' => $data['text'],
                            'account_id' => Auth::id(),
                            'comment_id' => $data['comment_id'],
                            'show' => $data['show'],
                            'pin' => $data['pin'],
                            'flag' => $data['flag'],
                            'like' => $data['like'],
                            'dislike' => $data['dislike'],

                        ]);

                        // Xử lý URL media
                        if (isset($data['media_urls'])) {
                            $commentUrlData = [];
                            foreach ($data['media_urls'] as $media) {
                                $commentUrlData[] = [
                                    'comment_reply_id' => $commentReply->id,
                                    'url' => $media['url'] ?? null,
                                ];
                            }
                            CommentUrl::insert($commentUrlData);
                        }

                        // Xử lý FILES media
                        if (isset($data['media_files'])) {
                            $commentFileData = [];
                            foreach ($data['media_files'] as $media) {
                                $commentFileData[] = [
                                    'comment_reply_id' => $commentReply->id,
                                    'file' => $media['file'] ?? null,
                                ];
                            }
                            CommentFile::insert($commentFileData);
                        }

                        Notification::make()
                            ->title('Phản hồi thành công!')
                            ->success()
                            ->send();

                    })->slideOver(),

                DeleteAction::make('Xóa')
            ])
        ];
    }

    public static function bulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
                BulkAction::make('Ẩn tất cả')
                    ->icon('heroicon-o-eye-slash')
                    ->color('primary')
                    ->modalIcon('heroicon-o-eye-slash')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update(['show' => false]);
                        });
                        Notification::make()
                            ->title('Ẩn thành công')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation(),
                BulkAction::make('Hiển thị tất cả')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->modalIcon('heroicon-o-eye')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update(['show' => true]);
                        });
                        Notification::make()
                            ->title('Hiện thành công')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation(),
            ]),
        ];
    }
}

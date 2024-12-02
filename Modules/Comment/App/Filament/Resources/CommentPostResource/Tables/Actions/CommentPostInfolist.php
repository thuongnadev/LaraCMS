<?php

namespace Modules\Comment\App\Filament\Resources\CommentPostResource\Tables\Actions;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageRepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Entities\CommentReply;
use Njxqlus\Filament\Components\Infolists\LightboxImageEntry;
use Filament\Infolists\Components\Tabs;

class CommentPostInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Thông tin bình luận')
                            ->icon('heroicon-m-identification')
                            ->schema([
                                Section::make('')->schema([
                                    TextEntry::make('commentable.title')
                                        ->label('Tên bài viết: ')
                                        ->weight(FontWeight::Bold)
                                        ->columnSpan(7),
                                    IconEntry::make('show')
                                        ->label('')
                                        ->boolean()
                                        ->tooltip(function ($record) {
                                            return $record->show ? 'Hiển thị' : 'Ẩn';
                                        })
                                        ->trueIcon('heroicon-o-eye')
                                        ->falseIcon('heroicon-o-eye-slash')
                                        ->alignCenter()
                                        ->columnSpan(1),
                                    TextEntry::make('likes_count')
                                        ->label('')
                                        ->getStateUsing(function (Comment $record): int {
                                            return $record->likes()->count();
                                        })
                                        ->badge()
                                        ->tooltip('Lượt thích')
                                        ->color('info')
                                        ->hidden(fn(Comment $record) => !$record->like)
                                        ->icon('heroicon-o-hand-thumb-up')
                                        ->weight(FontWeight::Bold)
                                        ->columnSpan(1),
                                    TextEntry::make('dislikes_count')
                                        ->label('')
                                        ->getStateUsing(function (Comment $record): int {
                                            return $record->dislikes()->count();
                                        })
                                        ->tooltip('Lượt không thích')
                                        ->hidden(fn(Comment $record) => !$record->dislike)
                                        ->badge()
                                        ->color('danger')
                                        ->icon('heroicon-o-hand-thumb-down')
                                        ->weight(FontWeight::Bold)
                                        ->columnSpan(1),
                                    TextEntry::make('report_count')
                                        ->label('')
                                        ->getStateUsing(function (Comment $record): int {
                                            return $record->reports()->count();
                                        })
                                        ->hidden(fn(Comment $record) => !$record->flag)
                                        ->tooltip('Lượt báo cáo')
                                        ->badge()
                                        ->color('warning')
                                        ->icon('heroicon-o-flag')
                                        ->weight(FontWeight::Bold)
                                        ->columnSpan(1),
                                    IconEntry::make('pin')
                                        ->label('')
                                        ->tooltip('Ghim bình luận')
                                        ->hidden(fn(Comment $record) => !$record->pin)
                                        ->default('')
                                        ->color(Color::Orange)
                                        ->icon('heroicon-o-bookmark')
                                        ->columnSpan(1),
                                    TextEntry::make('text')
                                        ->label('Nội dung: ')
                                        ->alignJustify()
                                        ->weight(FontWeight::Bold)
                                        ->columnSpan(12),
                                    ImageRepeatableEntry::make('urls')
                                        ->label('')
                                        ->schema([
                                            LightboxImageEntry::make('url')
                                                ->label('')
                                                ->limit(14)
                                                ->image(fn($record) => $record->url)
                                                ->href(fn($record) => $record->url)
                                                ->width(90)
                                                ->height(90)
                                                ->slideWidth('500px')
                                                ->slideHeight('500px')
                                                ->extraImgAttributes(['style' => 'border-radius: 10px;']),
                                        ])->columnSpan(12),

                                    ImageRepeatableEntry::make('files')
                                        ->label('')
                                        ->schema([
                                            LightboxImageEntry::make('file')
                                                ->label('')
                                                ->limit(14)
                                                ->image(fn($record) => $record->file)
                                                ->href(fn($record) => $record->file)
                                                ->width(90)
                                                ->height(90)
                                                ->slideWidth('500px')
                                                ->slideHeight('500px')
                                                ->extraImgAttributes(['style' => 'border-radius: 10px;']),
                                        ])->columnSpan(12)
                                ])->columnSpan(9),
                                Section::make('')->schema([
                                    TextEntry::make('account.name')
                                        ->label('Người bình luận: ')
                                        ->weight(FontWeight::Bold),
                                    TextEntry::make('created_at')
                                        ->label('Ngày tạo: ')
                                        ->badge()
                                        ->color('primary')
                                        ->dateTime(),
                                    TextEntry::make('updated_at')
                                        ->label('Ngày cập nhật: ')
                                        ->badge()
                                        ->color('primary')
                                        ->dateTime(),
                                ])->columnSpan(3),
                            ])->columns(12),
                        Tabs\Tab::make('Người phản hồi bình luận')
                            ->badge(function (Comment $record): int {
                                return $record->replies()->count();
                            })
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                RepeatableEntry::make('replies')
                                    ->label('')
                                    ->schema([
                                        Section::make('')->schema([
                                            TextEntry::make('account.name')
                                                ->label('Người phản hồi: ')
                                                ->weight(FontWeight::Bold)
                                                ->columnSpan(7),
                                            IconEntry::make('show')
                                                ->label('')
                                                ->boolean()
                                                ->tooltip(function ($record) {
                                                    return $record->show ? 'Hiển thị' : 'Ẩn';
                                                })
                                                ->trueIcon('heroicon-o-eye')
                                                ->falseIcon('heroicon-o-eye-slash')
                                                ->alignCenter()
                                                ->columnSpan(1),
                                            TextEntry::make('likes_count')
                                                ->label('')
                                                ->getStateUsing(function (CommentReply $record): int {
                                                    return $record->likes()->count();
                                                })
                                                ->badge()
                                                ->hidden(fn(CommentReply $record) => !$record->like)
                                                ->tooltip('Lượt thích')
                                                ->color('info')
                                                ->icon('heroicon-o-hand-thumb-up')
                                                ->weight(FontWeight::Bold)
                                                ->columnSpan(1),
                                            TextEntry::make('dislikes_count')
                                                ->label('')
                                                ->getStateUsing(function (CommentReply $record): int {
                                                    return $record->dislikes()->count();
                                                })
                                                ->tooltip('Lượt không thích')
                                                ->hidden(fn(CommentReply $record) => !$record->dislike)
                                                ->badge()
                                                ->color('danger')
                                                ->icon('heroicon-o-hand-thumb-down')
                                                ->weight(FontWeight::Bold)
                                                ->columnSpan(1),
                                            TextEntry::make('report_count')
                                                ->label('')
                                                ->getStateUsing(function (CommentReply $record): int {
                                                    return $record->reports()->count();
                                                })
                                                ->hidden(fn(CommentReply $record) => !$record->flag)
                                                ->tooltip('Lượt báo cáo')
                                                ->badge()
                                                ->color('warning')
                                                ->icon('heroicon-o-flag')
                                                ->weight(FontWeight::Bold)
                                                ->columnSpan(1),

                                            IconEntry::make('pin')
                                                ->label('')
                                                ->tooltip('Ghim bình luận')
                                                ->hidden(fn(CommentReply $record) => !$record->pin)
                                                ->default('')
                                                ->color(Color::Orange)
                                                ->icon('heroicon-o-bookmark')
                                                ->columnSpan(1),

                                            TextEntry::make('text')
                                                ->label('Nội dung: ')
                                                ->alignJustify()
                                                ->weight(FontWeight::Bold)
                                                ->columnSpan(12),

                                            ImageRepeatableEntry::make('urls')
                                                ->label('')
                                                ->schema([
                                                    LightboxImageEntry::make('url')
                                                        ->label('')
                                                        ->limit(7)
                                                        ->href(fn($record) => $record->url)
                                                        ->width(90)
                                                        ->height(90)
                                                        ->slideWidth('800px')
                                                        ->slideHeight('500px')
                                                        ->extraImgAttributes(['style' => 'border-radius: 10px;']),
                                                ])->columnSpan(12),

                                            ImageRepeatableEntry::make('files')
                                                ->label('')
                                                ->schema([
                                                    LightboxImageEntry::make('file')
                                                        ->label('')
                                                        ->limit(7)
                                                        ->image(fn($record) => $record->file)
                                                        ->href(fn($record) => $record->file)
                                                        ->width(90)
                                                        ->height(90)
                                                        ->slideWidth('500px')
                                                        ->slideHeight('500px')
                                                        ->extraImgAttributes(['style' => 'border-radius: 10px;']),
                                                ])->columnSpan(12)
                                        ])->columnSpan(9),
                                        Section::make('')->schema([
                                            TextEntry::make('created_at')
                                                ->label('Ngày tạo: ')
                                                ->badge()
                                                ->color('primary')
                                                ->dateTime(),
                                            TextEntry::make('updated_at')
                                                ->label('Ngày cập nhật: ')
                                                ->badge()
                                                ->color('primary')
                                                ->dateTime(),
                                        ])->columnSpan(3),
                                    ])->columns(12)
                            ]),
                    ])
                    ->activeTab(1)
                    ->columnSpan(12),
            ])->columns(12);
    }
}

<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Tables\Actions;

use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Thiktak\FilamentSimpleListEntry\Infolists\Components\SimpleListEntry;

class PricingInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Thông tin bảng giá')
                            ->icon('heroicon-m-identification')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Tên bảng giá: ')
                                    ->weight(FontWeight::Bold)
                                    ->columnSpan(4),
                                TextEntry::make('pricingGroup.name')
                                    ->label('Nhóm bảng giá')
                                    ->weight(FontWeight::Bold)
                                    ->state(function ($record) {
                                        if (!$record->pricingGroup) {
                                            return 'Không có dữ liệu';
                                        }
                                        return $record->pricingGroup->name ?? 'Không có dữ liệu';
                                    })
                                    ->color(function ($record) {
                                        if (!$record->pricingGroup || !$record->pricingGroup->name) {
                                            return 'danger';
                                        }
                                        return 'primary';
                                    })
                                    ->columnSpan(4),
                                TextEntry::make('pricingType.name')
                                    ->label('Loại bảng giá')
                                    ->weight(FontWeight::Bold)
                                    ->state(function ($record) {
                                        if (!$record->pricingType) {
                                            return 'Không có dữ liệu';
                                        }
                                        return $record->pricingType->name ?? 'Không có dữ liệu';
                                    })
                                    ->color(function ($record) {
                                        if (!$record->pricingType || !$record->pricingType->name) {
                                            return 'danger';
                                        }
                                        return 'primary';
                                    })
                                    ->columnSpan(3),
                                IconEntry::make('show')
                                    ->label(fn($record) => $record->show ? 'Hiển thị' : 'Ẩn')
                                    ->trueIcon('heroicon-o-eye')
                                    ->falseIcon('heroicon-o-eye-slash')
                                    ->alignCenter()
                                    ->columnSpan(1),
                                TextEntry::make('created_at')
                                    ->label('Ngày tạo: ')
                                    ->badge()
                                    ->color('primary')
                                    ->dateTime()
                                    ->columnSpan(4),
                                TextEntry::make('updated_at')
                                    ->label('Ngày cập nhật: ')
                                    ->badge()
                                    ->color('primary')
                                    ->dateTime()
                                    ->columnSpan(4),
                            ])->columns(12),
                        Tabs\Tab::make('Hiển thị theo bảng giá tên miền')
                            ->icon('heroicon-m-squares-2x2')
                            ->schema([
                                RepeatableEntry::make('pricingKeyValues')
                                    ->label('Danh sách nội dung bảng')
                                    ->schema([
                                        TextEntry::make('pricingKey.name')
                                            ->label('Từ khóa')
                                            ->state(function ($record) {
                                                if (!$record->pricingKey) {
                                                    return 'Không có dữ liệu';
                                                }
                                                if (!$record->pricingKey->status) {
                                                    return "Từ khóa {$record->pricingKey->name} đã bị tắt";
                                                }
                                                return $record->pricingKey->name ?? 'Không có dữ liệu';
                                            })
                                            ->color(function ($record) {
                                                if (!$record->pricingKey || !$record->pricingKey->status) {
                                                    return 'danger';
                                                }
                                                return 'primary';
                                            }),

                                        SimpleListEntry::make('pricingContent.meta')
                                            ->label('')
                                            ->listStyle('list')
                                            ->state(function ($record) {
                                                if (!$record->pricingContent) {
                                                    return 'Không có dữ liệu';
                                                }
                                                if (!$record->pricingContent->status) {
                                                    return "Giá trị {$record->pricingContent->name} đã bị tắt";
                                                }

                                                $meta = $record->pricingContent->meta;

                                                if (is_array($meta) && !empty($meta)) {
                                                    return implode(', ', $meta);
                                                }

                                                if (empty($meta)) {
                                                    return 'Không có giá trị';
                                                }

                                                return $meta;
                                            })
                                            ->badgeColor(function ($record) {
                                                if (!$record->pricingContent || !$record->pricingContent->status) {
                                                    return 'danger';
                                                }
                                                return 'primary';
                                            }),
                                    ])
                                    ->grid(4)
                                    ->columnSpanFull()
                                    ->visible(fn($record) => $record->pricingKeyValues->isNotEmpty())
                            ])->columns(12),
                        Tabs\Tab::make('Hiển thị theo bảng giá Hosting')
                            ->icon('heroicon-m-square-3-stack-3d')
                            ->schema([
                                RepeatableEntry::make('pricingKeyValues')
                                    ->label('Danh sách nội dung bảng')
                                    ->schema([
                                        TextEntry::make('pricingKey.name')
                                            ->label('Từ khóa')
                                            ->state(function ($record) {
                                                if (!$record->pricingKey) {
                                                    return 'Không có dữ liệu';
                                                }
                                                if (!$record->pricingKey->status) {
                                                    return "Từ khóa {$record->pricingKey->name} đã bị tắt";
                                                }
                                                return $record->pricingKey->name ?? 'Không có dữ liệu';
                                            })
                                            ->color(function ($record) {
                                                if (!$record->pricingKey || !$record->pricingKey->status) {
                                                    return 'danger';
                                                }
                                                return 'primary';
                                            })
                                            ->columnSpan(3),

                                        TextEntry::make('pricingValue.content')
                                            ->label('Giá trị')
                                            ->state(function ($record) {
                                                if (!$record->pricingValue) {
                                                    return 'Không có dữ liệu';
                                                }
                                                if (!$record->pricingValue->status) {
                                                    return "Giá trị {$record->pricingValue->name} đã bị tắt";
                                                }
                                                return $record->pricingValue->content ?? 'Không có dữ liệu';
                                            })
                                            ->color(function ($record) {
                                                if (!$record->pricingValue || !$record->pricingValue->status) {
                                                    return 'danger';
                                                }
                                                return 'primary';
                                            })
                                            ->columnSpan(3),

                                        ColorEntry::make('pricingContent.bg_color')
                                            ->label('')
                                            ->tooltip('Màu nền hàng')
                                            ->copyable()
                                            ->copyMessage('Đã sao chép!')
                                            ->copyMessageDuration(1500)
                                            ->columnSpan(1),

                                        ColorEntry::make('pricingContent.color_key')
                                            ->label('')
                                            ->tooltip('Màu chữ từ khóa')
                                            ->copyable()
                                            ->copyMessage('Đã sao chép!')
                                            ->copyMessageDuration(1500)
                                            ->columnSpan(1),

                                        ColorEntry::make('pricingContent.color_value')
                                            ->label('')
                                            ->tooltip('Màu chữ giá trị')
                                            ->copyable()
                                            ->copyMessage('Đã sao chép!')
                                            ->copyMessageDuration(1500)
                                            ->columnSpan(1),

                                        IconEntry::make('pricingContent.bold_key')
                                            ->label('')
                                            ->trueIcon('heroicon-o-bolt')
                                            ->falseIcon('heroicon-o-bolt-slash')
                                            ->alignCenter()
                                            ->tooltip(fn($record) => $record->pricingContent && $record->pricingContent->bold_key
                                                ? 'Hiển thị Chữ in đậm từ khóa'
                                                : 'Ẩn Chữ in đậm từ khóa')
                                            ->columnSpan(1),

                                        IconEntry::make('pricingContent.bold_value')
                                            ->label('')
                                            ->trueIcon('heroicon-o-bolt')
                                            ->falseIcon('heroicon-o-bolt-slash')
                                            ->tooltip(fn($record) => $record->pricingContent && $record->pricingContent->bold_value
                                                ? 'Hiển thị Chữ in đậm giá trị'
                                                : 'Ẩn Chữ in đậm giá trị')
                                            ->columnSpan(2),

                                        SimpleListEntry::make('pricingContent.meta')
                                            ->label('')
                                            ->listStyle('list')
                                            ->state(function ($record) {
                                                if (!$record->pricingContent) {
                                                    return 'Không có dữ liệu';
                                                }
                                                if (!$record->pricingContent->status) {
                                                    return "Giá trị {$record->pricingContent->name} đã bị tắt";
                                                }

                                                $meta = $record->pricingContent->meta;

                                                if (is_array($meta) && !empty($meta)) {
                                                    return implode(', ', $meta);
                                                }

                                                if (empty($meta)) {
                                                    return 'Không có giá trị';
                                                }

                                                return $meta;
                                            })
                                            ->badgeColor(function ($record) {
                                                if (!$record->pricingContent || !$record->pricingContent->status) {
                                                    return 'danger';
                                                }
                                                return 'primary';
                                            })->columnSpan(12),
                                    ])
                                    ->grid(2)
                                    ->columnSpanFull()
                                    ->visible(fn($record) => $record->pricingKeyValues->isNotEmpty())
                            ])->columns(12),
                        Tabs\Tab::make('Hiển thị theo bảng giá Website')
                            ->icon('heroicon-m-view-columns')
                            ->schema([
                                RepeatableEntry::make('pricingKeyValues')
                                    ->label('Danh sách nội dung bảng')
                                    ->schema([
                                        TextEntry::make('pricingKey.name')
                                            ->label('Từ khóa')
                                            ->state(function ($record) {
                                                if (!$record->pricingKey) {
                                                    return 'Không có dữ liệu';
                                                }
                                                if (!$record->pricingKey->status) {
                                                    return "Từ khóa {$record->pricingKey->name} đã bị tắt";
                                                }
                                                return $record->pricingKey->name ?? 'Không có dữ liệu';
                                            })
                                            ->color(function ($record) {
                                                if (!$record->pricingKey || !$record->pricingKey->status) {
                                                    return 'danger';
                                                }
                                                return 'primary';
                                            })
                                            ->columnSpan(6),

                                        IconEntry::make('pricingValue.is_check')
                                            ->label('')
                                            ->trueIcon('heroicon-o-check')
                                            ->falseIcon('heroicon-o-x-mark')
                                            ->alignCenter()
                                            ->tooltip(function ($record) {
                                                if (!$record->pricingValue) {
                                                    return 'Không có dữ liệu';
                                                }
                                                if (!$record->pricingValue->status) {
                                                    return "Giá trị đã bị tắt";
                                                }
                                                return $record->pricingValue->is_check ? 'Đã tích' :  'Không tích';
                                            })
                                            ->icon(function ($record) {
                                                if (!$record->pricingValue || !$record->pricingValue->status) {
                                                    return 'heroicon-o-shield-exclamation'; // Sử dụng biểu tượng shield-exclamation khi bị tắt
                                                }
                                                return null; // Không hiển thị biểu tượng nếu giá trị hoạt động
                                            })
                                            ->color(function ($record) {
                                                if (!$record->pricingValue || !$record->pricingValue->status) {
                                                    return 'danger'; // Màu sắc nguy hiểm khi bị tắt
                                                }
                                                return $record->pricingValue->is_check ? 'success' : 'danger';
                                            })
                                            ->trueColor('success')
                                            ->falseColor('danger')
                                            ->alignCenter()
                                            ->columnSpan(1),
                                    ])
                                    ->grid(5)
                                    ->columnSpanFull()
                                    ->visible(fn($record) => $record->pricingKeyValues->isNotEmpty())
                            ])->columns(12),
                        Tabs\Tab::make('Hiển thị mặc định')
                            ->icon('heroicon-m-wallet')
                            ->schema([
                                RepeatableEntry::make('pricingKeyValues')
                                    ->label('Danh sách nội dung bảng')
                                    ->schema([
                                        Grid::make(4)->schema([
                                            TextEntry::make('pricingKey.name')
                                                ->label('Từ khóa')
                                                ->state(function ($record) {
                                                    if (!$record->pricingKey) {
                                                        return 'Không có dữ liệu';
                                                    }
                                                    if (!$record->pricingKey->status) {
                                                        return "Từ khóa {$record->pricingKey->name} đã bị tắt";
                                                    }
                                                    return $record->pricingKey->name ?? 'Không có dữ liệu';
                                                })
                                                ->color(function ($record) {
                                                    if (!$record->pricingKey || !$record->pricingKey->status) {
                                                        return 'danger';
                                                    }
                                                    return 'primary';
                                                }),
                                            TextEntry::make('pricingValue.content')
                                                ->label('Giá trị')
                                                ->state(function ($record) {
                                                    if (!$record->pricingValue) {
                                                        return 'Không có dữ liệu';
                                                    }
                                                    if (!$record->pricingValue->status) {
                                                        return "Giá trị {$record->pricingValue->name} đã bị tắt";
                                                    }
                                                    return $record->pricingValue->content ?? 'Không có dữ liệu';
                                                })
                                                ->color(function ($record) {
                                                    if (!$record->pricingValue || !$record->pricingValue->status) {
                                                        return 'danger';
                                                    }
                                                    return 'primary';
                                                }),

                                            IconEntry::make('pricingContent.bold_key')
                                                ->label(function ($record) {
                                                    if (is_null($record->pricing_content_id)) {
                                                        return 'Không có dữ liệu'; // Hiển thị nhãn "Không có dữ liệu" nếu pricing_content_id là null
                                                    }
                                                    if (!$record->pricingContent) {
                                                        return 'Không có dữ liệu';
                                                    }
                                                    if (!$record->pricingContent->status) {
                                                        return 'Chữ in đậm từ khóa đã bị tắt';
                                                    }
                                                    return $record->pricingContent->bold_key
                                                        ? 'Hiển thị Chữ in đậm từ khóa'
                                                        : 'Ẩn Chữ in đậm từ khóa';
                                                })
                                                ->trueIcon('heroicon-o-bolt')
                                                ->falseIcon('heroicon-o-bolt-slash')
                                                ->alignCenter(),

                                            IconEntry::make('pricingContent.bold_value')
                                                ->label(function ($record) {
                                                    if (is_null($record->pricing_content_id)) {
                                                        return 'Không có dữ liệu'; // Hiển thị nhãn "Không có dữ liệu" nếu pricing_content_id là null
                                                    }
                                                    if (!$record->pricingContent) {
                                                        return 'Không có dữ liệu';
                                                    }
                                                    if (!$record->pricingContent->status) {
                                                        return 'Chữ in đậm giá trị đã bị tắt';
                                                    }
                                                    return $record->pricingContent->bold_value
                                                        ? 'Hiển thị Chữ in đậm giá trị'
                                                        : 'Ẩn Chữ in đậm giá trị';
                                                })
                                                ->trueIcon('heroicon-o-bolt')
                                                ->falseIcon('heroicon-o-bolt-slash'),

                                            ColorEntry::make('pricingContent.bg_color')
                                                ->label(function ($record) {
                                                    if (is_null($record->pricing_content_id)) {
                                                        return 'Không có dữ liệu'; // Hiển thị nhãn "Không có dữ liệu" nếu pricing_content_id là null
                                                    }
                                                    if (!$record->pricingContent || !$record->pricingContent->status) {
                                                        return 'Màu nền hàng đã bị tắt';
                                                    }
                                                    return 'Màu nền hàng';
                                                })
                                                ->copyable()
                                                ->copyMessage('Đã sao chép!')
                                                ->copyMessageDuration(1500),

                                            ColorEntry::make('pricingContent.color_key')
                                                ->label(function ($record) {
                                                    if (is_null($record->pricing_content_id)) {
                                                        return 'Không có dữ liệu'; // Hiển thị nhãn "Không có dữ liệu" nếu pricing_content_id là null
                                                    }
                                                    if (!$record->pricingContent || !$record->pricingContent->status) {
                                                        return 'Màu chữ từ khóa đã bị tắt';
                                                    }
                                                    return 'Màu chữ từ khóa';
                                                })
                                                ->copyable()
                                                ->copyMessage('Đã sao chép!')
                                                ->copyMessageDuration(1500),

                                            ColorEntry::make('pricingContent.color_value')
                                                ->label(function ($record) {
                                                    if (is_null($record->pricing_content_id)) {
                                                        return 'Không có dữ liệu'; // Hiển thị nhãn "Không có dữ liệu" nếu pricing_content_id là null
                                                    }
                                                    if (!$record->pricingContent || !$record->pricingContent->status) {
                                                        return 'Màu chữ giá trị đã bị tắt';
                                                    }
                                                    return 'Màu chữ giá trị';
                                                })
                                                ->copyable()
                                                ->copyMessage('Đã sao chép!')
                                                ->copyMessageDuration(1500),

                                            IconEntry::make('pricingValue.is_check')
                                                ->label(function ($record) {
                                                    if (is_null($record->pricing_value_id)) {
                                                        return 'Không có dữ liệu'; // Hiển thị nhãn "Không có dữ liệu" nếu pricing_value_id là null
                                                    }

                                                    // Kiểm tra giá trị của pricingValue và cập nhật nhãn tương ứng
                                                    if (!$record->pricingValue) {
                                                        return 'Không có dữ liệu';
                                                    }
                                                    if (!$record->pricingValue->status) {
                                                        return "Dấu tích đã bị tắt";
                                                    }
                                                    return $record->pricingValue->is_check ? 'Đã tích' : 'Không tích';
                                                })
                                                ->trueIcon('heroicon-o-check')
                                                ->falseIcon('heroicon-o-x-mark')
                                                ->alignCenter()
                                                ->icon(function ($record) {
                                                    if (!$record->pricingValue || !$record->pricingValue->status) {
                                                        return 'heroicon-o-shield-exclamation'; // Sử dụng biểu tượng shield-exclamation khi bị tắt
                                                    }
                                                    return null; // Không hiển thị biểu tượng nếu giá trị hoạt động
                                                })
                                                ->color(function ($record) {
                                                    if (!$record->pricingValue || !$record->pricingValue->status) {
                                                        return 'danger'; // Màu sắc nguy hiểm khi bị tắt
                                                    }
                                                    return $record->pricingValue->is_check ? 'success' : 'danger';
                                                })
                                                ->trueColor('success')
                                                ->falseColor('danger')
                                                ->alignCenter(),

                                            SimpleListEntry::make('pricingContent.meta')
                                                ->label('')
                                                ->listStyle('list')
                                                ->state(function ($record) {
                                                    if (!$record->pricingContent) {
                                                        return 'Không có dữ liệu';
                                                    }
                                                    if (!$record->pricingContent->status) {
                                                        return "Giá trị {$record->pricingContent->name} đã bị tắt";
                                                    }

                                                    $meta = $record->pricingContent->meta;

                                                    if (is_array($meta) && !empty($meta)) {
                                                        return implode(', ', $meta);
                                                    }

                                                    if (empty($meta)) {
                                                        return 'Không có giá trị';
                                                    }

                                                    return $meta;
                                                })
                                                ->columnSpanFull()
                                                ->badgeColor(function ($record) {
                                                    if (!$record->pricingContent || !$record->pricingContent->status) {
                                                        return 'danger';
                                                    }
                                                    return 'primary';
                                                }),

                                        ]),
                                    ])
                                    ->grid(1)
                                    ->columnSpanFull()
                                    ->visible(fn($record) => $record->pricingKeyValues->isNotEmpty())
                            ])->columns(12),
                    ])
                    ->activeTab(1)->columnSpan(12),
            ])->columns(12);
    }
}

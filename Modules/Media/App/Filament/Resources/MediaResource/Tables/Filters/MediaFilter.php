<?php

namespace Modules\Media\App\Filament\Resources\MediaResource\Tables\Filters;


use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Modules\Media\Entities\Media;
use Modules\Media\Entities\MediaHasModel;
use Modules\Post\Entities\Post;
use Modules\Product\Entities\Product;
use Illuminate\Support\Str;


class MediaFilter
{
    public static function filter(): array
    {
        return [
            Filter::make('date_range')
                ->form([
                    Select::make('date_option')
                        ->label('Chọn ngày')
                        ->options([
                            'today' => 'Hôm nay',
                            'last_7_days' => '7 ngày qua',
                            'this_month' => 'Tháng này',
                            'last_month' => 'Tháng trước',
                            'custom' => 'Tùy chỉnh',
                        ])
                        ->reactive()
                        ->afterStateUpdated(fn($state, callable $set) => $set('custom_range_visible', $state === 'custom')),
                    DatePicker::make('custom_from')
                        ->label('Từ ngày')
                        ->visible(fn($get) => $get('date_option') === 'custom'),
                    DatePicker::make('custom_until')
                        ->label('Đến ngày')
                        ->visible(fn($get) => $get('date_option') === 'custom'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['date_option'], function ($query, $option) use ($data) {
                        $now = Carbon::now();
                        switch ($option) {
                            case 'today':
                                return $query->whereDate('created_at', $now);
                            case 'last_7_days':
                                return $query->whereBetween('created_at', [$now->copy()->subDays(6)->startOfDay(), $now->endOfDay()]);
                            case 'this_month':
                                return $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
                            case 'last_month':
                                return $query->whereMonth('created_at', $now->subMonth()->month)->whereYear('created_at', $now->year);
                            case 'custom':
                                return $query->when($data['custom_from'], fn($q) => $q->whereDate('created_at', '>=', $data['custom_from']))
                                    ->when($data['custom_until'], fn($q) => $q->whereDate('created_at', '<=', $data['custom_until']));
                        }
                    });
                })
                ->indicateUsing(function (array $data): ?string {
                    if ($data['date_option'] === 'custom' && ($data['custom_from'] || $data['custom_until'])) {
                        return 'Từ ' . ($data['custom_from'] ?? 'không giới hạn') . ' đến ' . ($data['custom_until'] ?? 'không giới hạn');
                    }
                    return $data['date_option'] ? __($data['date_option']) : null;
                }),

            SelectFilter::make('module')
                ->label('Loại nội dung')
                ->options(function () {
                    return self::getModuleOptions();
                })
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['value'], function ($query, $modelType) {
                        return $query->whereHas('mediaHasModels', function ($query) use ($modelType) {
                            $query->where('model_type', $modelType);
                        });
                    });
                }),
        ];
    }

    private static function getModuleOptions(): array
    {
        $modules = MediaHasModel::distinct()
            ->pluck('model_type')
            ->mapWithKeys(function ($modelType) {
                return [$modelType => self::getVietnameseName($modelType)];
            })
            ->toArray();

        return $modules;
    }

    private static function getVietnameseName($modelType): string
    {
        $moduleName = Str::afterLast($modelType, '\\');
        return self::autoTranslateToVietnamese($moduleName);
    }

    private static function autoTranslateToVietnamese($name): string
    {
        $dictionary = [
            'product' => 'sản phẩm',
            'post' => 'bài viết',
            'category' => 'danh mục',
            'user' => 'người dùng',
            'comment' => 'bình luận',
            'order' => 'đơn hàng',
            'customer' => 'khách hàng',
        ];

        $words = preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY);
        $translatedWords = array_map(function ($word) use ($dictionary) {
            $lowercaseWord = strtolower($word);
            return $dictionary[$lowercaseWord] ?? $lowercaseWord;
        }, $words);

        return ucfirst(implode(' ', $translatedWords));
    }
}

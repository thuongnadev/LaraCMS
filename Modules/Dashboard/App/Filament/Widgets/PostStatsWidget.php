<?php

namespace Modules\Dashboard\App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Post\Entities\Post;
use Illuminate\Support\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Forms\Components\Select;

class PostStatsWidget extends ChartWidget
{
    protected static ?string $heading = 'Thống kê bài viết';

    public ?string $filter = null;

    public function mount(): void
    {
        Carbon::setLocale('vi');
    }

    protected function getFilters(): array
    {
        $months = Post::selectRaw('DISTINCT DATE_FORMAT(created_at, "%Y-%m") as month')
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->map(function ($month) {
                $date = Carbon::createFromFormat('Y-m', $month);
                return ucfirst($date->isoFormat('MMMM YYYY'));
            })
            ->take(12) 
            ->toArray();

        return ['all' => 'Tất cả'] + array_combine($months, $months);
    }

    protected function getData(): array
    {
        $selectedMonth = $this->filter ?? 'all';
        $statuses = ['draft', 'published', 'archived'];
        $datasets = [];

        foreach ($statuses as $status) {
            $query = Post::where('status', $status);
            
            if ($selectedMonth !== 'all') {
                $startDate = Carbon::createFromLocaleIsoFormat('MMMM YYYY', 'vi', $selectedMonth)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $data = Trend::query($query)
                    ->between(start: $startDate, end: $endDate)
                    ->perDay()
                    ->count();
            } else {
                $data = Trend::query($query)
                    ->between(
                        start: now()->startOfYear(),
                        end: now(),
                    )
                    ->perMonth()
                    ->count();
            }

            $datasets[] = [
                'label' => $this->getStatusLabel($status),
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                'borderColor' => $this->getColorForStatus($status),
                'fill' => false,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $data->map(fn (TrendValue $value) => $selectedMonth === 'all' 
                ? Carbon::parse($value->date)->isoFormat('MMMM YYYY') 
                : Carbon::parse($value->date)->isoFormat('DD/MM/YYYY')),
        ];
    }

    protected function getStatusLabel($status): string
    {
        return match ($status) {
            'draft' => 'Bản nháp',
            'published' => 'Đã xuất bản',
            'archived' => 'Đã lưu trữ',
            default => ucfirst($status),
        };
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('filter')
                ->label('Chọn tháng')
                ->options($this->getFilters())
                ->default('all')
                ->reactive()
                ->afterStateUpdated(fn () => $this->updateChartData()),
        ];
    }

    protected function getColorForStatus($status): string
    {
        return match ($status) {
            'draft' => 'rgb(255, 99, 132)',
            'published' => 'rgb(54, 162, 235)',
            'archived' => 'rgb(255, 205, 86)',
            default => 'rgb(201, 203, 207)',
        };
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}

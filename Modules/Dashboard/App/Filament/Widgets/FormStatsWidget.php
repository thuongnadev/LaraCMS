<?php

namespace Modules\Dashboard\App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Form\Entities\Form;
use Modules\Form\Entities\FormSubmission;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\Select;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class FormStatsWidget extends ChartWidget
{
    protected static ?string $heading = 'Thống kê phản hồi biểu mẫu';

    public ?string $filter = null;

    public function mount(): void
    {
        Carbon::setLocale('vi');
    }

    protected function getFilters(): array
    {
        $months = FormSubmission::selectRaw('DISTINCT DATE_FORMAT(created_at, "%Y-%m") as month')
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
        $forms = Form::all();
        $datasets = [];
        $labels = [];

        foreach ($forms as $index => $form) {
            $query = FormSubmission::where('form_id', $form->id);

            if ($selectedMonth !== 'all') {
                $startDate = Carbon::createFromLocaleIsoFormat('MMMM YYYY', 'vi', $selectedMonth)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $data = Trend::query($query)
                    ->between(
                        start: $startDate->startOfDay(),
                        end: $endDate->endOfDay()
                    )
                    ->perDay()
                    ->count();
            } else {
                $data = Trend::query($query)
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfDay()
                    )
                    ->perMonth()
                    ->count();
            }

            $datasets[] = [
                'label' => $form->name,
                'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                'borderColor' => $this->getColorForForm($index),
                'fill' => false,
            ];

            if (empty($labels)) {
                $labels = $data->map(fn(TrendValue $value) => $selectedMonth === 'all'
                    ? Carbon::parse($value->date)->isoFormat('MMMM YYYY')
                    : Carbon::parse($value->date)->isoFormat('DD/MM/YYYY'));
            }
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('filter')
                ->label('Chọn tháng')
                ->options($this->getFilters())
                ->default('all')
                ->reactive()
                ->afterStateUpdated(fn() => $this->updateChartData()),
        ];
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

    protected function getColorForForm($index): string
    {
        $colors = [
            '#4CAF50',
            '#2196F3',
            '#FFC107',
            '#E91E63',
            '#9C27B0',
            '#FF5722',
            '#795548',
            '#607D8B',
            '#3F51B5',
            '#00BCD4',
        ];
        return $colors[$index % count($colors)];
    }

    public function getColumnSpan(): int | string | array
    {
        return 1;
    }
}

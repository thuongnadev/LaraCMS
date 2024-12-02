<?php

namespace Modules\Dashboard\App\Filament\Pages;

use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Modules\Dashboard\App\Filament\Widgets\ATotalWidgets;
use Modules\Dashboard\App\Filament\Widgets\FormStatsWidget;
use Modules\Dashboard\App\Filament\Widgets\PostStatsWidget;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'xl' => 3,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ATotalWidgets::class,
        ];
    }

    protected function getWidgets(): array  
    {
        return [
            PostStatsWidget::class,
            FormStatsWidget::class
        ];
    }

}
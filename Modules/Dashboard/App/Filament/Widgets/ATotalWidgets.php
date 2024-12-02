<?php

namespace Modules\Dashboard\App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Modules\Post\Entities\Post;
use Modules\Form\Entities\FormSubmission;
use Modules\ProductVps\Entities\ProductVps;
use Carbon\Carbon;

class ATotalWidgets extends BaseWidget
{

    public static function canViewWidget(): bool
    {
        return true;
    }

    protected function getColumns(): int
    {
        return 3;
    }

    
    protected function getStats(): array
    {
        $now = Carbon::now();
        $lastWeek = $now->copy()->subWeek();

        return [
            $this->getPostStats($now, $lastWeek),
            $this->getProductStats($now, $lastWeek),
            $this->getFormSubmissionStats($now, $lastWeek),
        ];
    }

    private function getPostStats(Carbon $now, Carbon $lastWeek): Stat
    {
        $totalPosts = Post::count();
        $recentPosts = Post::where('created_at', '>=', $lastWeek)->count();
        $latestPost = Post::latest('created_at')->first();

        return Stat::make('Bài viết', $totalPosts)
            ->description("$recentPosts bài viết mới trong 7 ngày qua")
            ->descriptionIcon('heroicon-m-document-text')
            ->chart($this->getChartData(Post::class))
            ->color('primary')
            ->extraAttributes([
                'tooltip' => $latestPost ? "Bài viết mới nhất: {$latestPost->title}" : null,
            ]);
    }

    private function getProductStats(Carbon $now, Carbon $lastWeek): Stat
    {
        $totalProducts = ProductVps::count();
        $recentProducts = ProductVps::where('created_at', '>=', $lastWeek)->count();
        $latestProduct = ProductVps::latest('created_at')->first();

        return Stat::make('Sản phẩm', $totalProducts)
            ->description("$recentProducts sản phẩm mới trong 7 ngày qua")
            ->descriptionIcon('heroicon-m-shopping-bag')
            ->chart($this->getChartData(ProductVps::class))
            ->color('success')
            ->extraAttributes([
                'tooltip' => $latestProduct ? "Sản phẩm mới nhất: {$latestProduct->name}" : null,
            ]);
    }

    private function getFormSubmissionStats(Carbon $now, Carbon $lastWeek): Stat
    {
        $totalSubmissions = FormSubmission::count();
        $recentSubmissions = FormSubmission::where('created_at', '>=', $lastWeek)->count();

        return Stat::make('Phản hồi', $totalSubmissions)
            ->description("$recentSubmissions phản hồi mới trong 7 ngày qua")
            ->descriptionIcon('heroicon-m-chat-bubble-left-right')
            ->chart($this->getChartData(FormSubmission::class))
            ->color('warning');
    }

    private function getChartData($model): array
    {
        return $model::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
    }
}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Theme\Http\Controllers\ThemeController;
use Modules\Menu\Entities\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

function formatMenu(Collection $items): array
{
    return $items->map(function ($item) {
        $page = [
            'name' => $item['title'],
            'url' => $item['url'],
            'page_id' => $item['page_id'],
        ];

        if (isset($item['params'])) {
            $page['params'] = $item['params'];
        }

        if (isset($item['children']) && $item['children'] instanceof Collection && $item['children']->isNotEmpty()) {
            $page['children'] = formatMenu($item['children']);
        }

        return $page;
    })->toArray();
}

function createRoutes(array $pages, string $prefix = ''): void
{
    foreach ($pages as $page) {
        if (isset($page['url'])) {
            $url = '/' . trim($prefix . '/' . trim($page['url'], '/'), '/');

            $route = Route::get($url, [ThemeController::class, 'index'])
                ->name('page.' . str_replace(['{', '}'], '', trim($url, '/')))
                ->defaults('page_id', $page['page_id']);

            if (isset($page['params']) && is_array($page['params'])) {
                $route->where($page['params']);
            }
        }

        if (!empty($page['children'])) {
            createRoutes($page['children'], $url ?? $prefix);
        }
    }
}

$menu = Menu::query()
    ->with([
        'menuItems' => function ($query) {
            $query->whereNull('parent_id')
                ->orderBy('order')
                ->with(['children' => function ($query) {
                    $query->orderBy('order')
                        ->with(['children' => function ($query) {
                            $query->orderBy('order');
                        }]);
                }]);
        },
        'locations' => function ($query) {
            $query->whereIn('location', ['header', 'footer']);
        }
    ])
    ->whereHas('locations', function ($query) {
        $query->whereIn('location', ['header', 'footer']);
    })
    ->where('is_visible', true)
    ->first();

if ($menu && $menu->menuItems) {
    $pages = formatMenu($menu->menuItems);
    createRoutes($pages);
} else {
    Route::get('/', [ThemeController::class, 'index'])->name('home');
}

Route::get('/bai-viet/{slug}', [ThemeController::class, 'postDetail'])->name('post.detail');
Route::get('/san-pham/{slug}', [ThemeController::class, 'productDetail'])->name('product.detail');
Route::get('/kiem-tra-ten-mien', [ThemeController::class, 'domainLookupDetail'])->name('domain-lookup.detail');
Route::get('/api/check-domain', function (Request $request) {
    $domain = $request->query('domain');
    $response = Http::get('https://tracking-domain.goldenbeeltd.vn/', [
        'domain' => $domain
    ]);
    return $response->json();
});

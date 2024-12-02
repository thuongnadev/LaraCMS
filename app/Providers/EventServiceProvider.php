<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Page\Entities\Page;
use Modules\Menu\Entities\MenuItem;
use Modules\Post\Entities\Post;
use Modules\ProductVps\Entities\ProductVps;
use App\Observers\PageObserver;
use App\Observers\MenuItemObserver;
use App\Observers\PostObserver;
use App\Observers\ProductVpsObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        Page::observe(PageObserver::class);
        MenuItem::observe(MenuItemObserver::class);
        Post::observe(PostObserver::class);
        ProductVps::observe(ProductVpsObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

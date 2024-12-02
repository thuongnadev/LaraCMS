<?php

namespace Modules\ProductVps\App\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class ProductVpsPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'ProductVps';
    }

    public function getId(): string
    {
        return 'productvps';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}

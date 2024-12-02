<?php

namespace Modules\Pricing\App\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class PricingPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Pricing';
    }

    public function getId(): string
    {
        return 'pricing';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}

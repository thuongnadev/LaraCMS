<?php

namespace Modules\Footer\App\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FooterPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Footer';
    }

    public function getId(): string
    {
        return 'footer';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}

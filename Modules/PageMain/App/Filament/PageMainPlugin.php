<?php

namespace Modules\PageMain\App\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class PageMainPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'PageMain';
    }

    public function getId(): string
    {
        return 'pagemain';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
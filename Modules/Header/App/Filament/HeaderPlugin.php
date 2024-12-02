<?php

namespace Modules\Header\App\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class HeaderPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Header';
    }

    public function getId(): string
    {
        return 'header';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}

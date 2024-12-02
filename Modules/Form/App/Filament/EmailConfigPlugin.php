<?php

namespace Modules\Form\App\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class EmailConfigPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Form';
    }

    public function getId(): string
    {
        return 'emailconfig';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
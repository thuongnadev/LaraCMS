<?php

namespace Modules\Setting\App\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class SettingPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Setting';
    }

    public function getId(): string
    {
        return 'setting';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}

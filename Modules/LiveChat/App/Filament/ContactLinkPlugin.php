<?php

namespace Modules\LiveChat\App\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class ContactLinkPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'LiveChat';
    }

    public function getId(): string
    {
        return 'contactlink';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
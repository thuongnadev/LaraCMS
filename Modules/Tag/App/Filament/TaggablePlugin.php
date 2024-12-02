<?php

namespace Modules\Tag\App\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class TaggablePlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Tag';
    }

    public function getId(): string
    {
        return 'taggable';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
<?php

namespace Modules\Media\App\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class MediaPlugin implements Plugin
{
    use ModuleFilamentPlugin;


    public function getModuleName(): string
    {
        return 'Media';
    }

    public function getId(): string
    {
        return 'media';
    }

    public function boot(Panel $panel): void
    {
    
    }

}


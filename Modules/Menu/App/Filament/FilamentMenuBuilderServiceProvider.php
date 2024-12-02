<?php

declare(strict_types=1);

namespace Datlechin\FilamentMenuBuilder;

use Livewire\Livewire;
use Modules\Menu\Livewire\CreateCustomLink;
use Modules\Menu\Livewire\MenuItems;
use Modules\Menu\Livewire\Panel;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMenuBuilderServiceProvider extends PackageServiceProvider
{
    public static string $name = 'menu';

    public static string $viewNamespace = 'menu';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('Modules/menu');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        Livewire::component('menu-builder-items', MenuItems::class);
        Livewire::component('menu-builder-panel', Panel::class);
        Livewire::component('create-custom-link', CreateCustomLink::class);
    }

    protected function getMigrations(): array
    {
        return [
            'create_menus_table',
        ];
    }
}

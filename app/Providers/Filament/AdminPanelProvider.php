<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\ThemeMode;
use Filament\Facades\Filament;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Pages\Auth\EditProfile;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Modules\Category\App\Filament\CategoryPlugin;
use Modules\Form\App\Filament\EmailSettingPlugin;
use Modules\Form\App\Filament\FormNotificationPlugin;
use Modules\Form\App\Filament\FormPlugin;
use Modules\Component\App\Filament\ComponentPlugin;
use Modules\Dashboard\App\Filament\Widgets\ATotalWidgets;
use Modules\Dashboard\App\Filament\Widgets\FormStatsWidget;
use Modules\Dashboard\App\Filament\Widgets\PostStatsWidget;
use Modules\Footer\App\Filament\FooterPlugin;
use Modules\Form\App\Filament\EmailConfigPlugin;
use Modules\Form\App\Filament\FormSubmissionPlugin;
use Modules\Header\App\Filament\HeaderPlugin;
use Modules\LiveChat\App\Filament\ContactLinkPlugin;
use Modules\Media\App\Filament\MediaPlugin;
use Modules\Menu\App\Filament\FilamentMenuBuilderPlugin;
use Modules\Page\App\Filament\PagePlugin;
use Modules\PageMain\App\Filament\PageMainPlugin;
use Modules\Post\App\Filament\PostPlugin;
use Modules\Pricing\App\Filament\PricingPlugin;
use Modules\Process\App\Filament\ProcessPlugin;
use Modules\ProductVps\App\Filament\ProductVpsPlugin;
use Modules\Tag\App\Filament\TagPlugin;
use Modules\SettingCompany\App\Filament\SettingCompanyPlugin;
use Modules\User\App\Filament\UserPlugin;
use Modules\Setting\App\Filament\SettingPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $themeSettings = config('theme');
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => $themeSettings['primary_color'] ?? Color::Indigo,
                'secondary' => $themeSettings['secondary_color'] ?? Color::Gray,
                'info' => $themeSettings['info_color'] ?? Color::Blue,
                'success' => $themeSettings['success_color'] ?? Color::Emerald,
                'danger' => $themeSettings['danger_color'] ?? Color::Rose,
                'warning' => $themeSettings['warning_color'] ?? Color::Orange,
            ])
            ->favicon(asset('storage/' . $themeSettings['favicon'] ?? ''))
            ->brandLogo(asset('storage/' . $themeSettings['logo'] ?? ''))
            ->brandLogoHeight($themeSettings['logo_size'] . 'px' ??'2rem')
            ->defaultThemeMode(ThemeMode::Light)
            ->maxContentWidth($themeSettings['layout'] === 'boxed' ? '7xl' : null)
            ->font($themeSettings['font_family'] ?? 'Poppins', provider: GoogleFontProvider::class)
            ->profile(EditProfile::class)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                ATotalWidgets::class,
                PostStatsWidget::class,
                FormStatsWidget::class,
                PostStatsWidget::class
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                UserPlugin::make(),
                MediaPlugin::make(),
                PostPlugin::make(),
                TagPlugin::make(),
                CategoryPlugin::make(),
                FormPlugin::make(),
                FormSubmissionPlugin::make(),
                EmailSettingPlugin::make(),
                FormNotificationPlugin::make(),
                PagePlugin::make(),
                ComponentPlugin::make(),
                SettingCompanyPlugin::make(),
                ComponentPlugin::make(),
                FilamentMenuBuilderPlugin::make()
                    ->addLocation('header', 'Header')
                    ->addLocation('footer', 'Footer'),
                ComponentPlugin::make(),
                ProductVpsPlugin::make(),
                PricingPlugin::make(),
                ProcessPlugin::make(),
                FormSubmissionPlugin::make(),
                HeaderPlugin::make(),
                FooterPlugin::make(),
                EmailConfigPlugin::make(),
                SettingPlugin::make(),
                PageMainPlugin::make(),
                ContactLinkPlugin::make()
            ])
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::Full)
            ->viteTheme('resources/css/filament/admin/theme.css');
    }

    public function boot(): void
    {
        Filament::serving(function () {
            $customCss = config('theme.custom_css');
            if (!empty($customCss)) {
                Filament::registerTheme(asset($customCss));
                Filament::registerViteTheme('resources/css/filament/admin/theme.css');
            }

            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label('Cài đặt giao diện')
                    ->url('/admin/theme/setting')
                    ->icon('heroicon-s-cog'),
            ]);
        });

        FilamentAsset::register([
            Css::make('filament-theme/custom', public_path('css/thuongna/filament-theme/custom.css')),
        ], package: 'thuongna');
    }
}

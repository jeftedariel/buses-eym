<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\InventoryStatsOverviewWidget;
use App\Filament\Widgets\LowStockSuppliesWidget;
use App\Filament\Widgets\RecentStatusChangesWidget;
use App\Filament\Widgets\ToolStatusChangesWidget;
use App\Filament\Widgets\ToolStatusChartWidget;
use App\Filament\Widgets\VehicleStatusChartWidget;
use App\Models\Vehicle;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jacobtims\FilamentLogger\FilamentLoggerPlugin;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use TomatoPHP\FilamentUsers\FilamentUsersPlugin;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->globalSearch(false)
            ->id('dashboard')
            ->path('dashboard')
            ->viteTheme('resources/css/filament/dashboard/theme.css')
            ->login()
            ->brandName('Buses E&M')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('40px')
            ->favicon(asset('images/logo.png'))
           ->viteTheme('resources/css/filament/dashboard/theme.css') // ← Añade esta línea
           ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Cyan,  // Cambiado de Blue a Cyan para un tono más celeste
                'primary' => Color::Sky,  // Cambiado de Indigo a Sky para un azul más claro
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                InventoryStatsOverviewWidget::class,
                LowStockSuppliesWidget::class,
                RecentStatusChangesWidget::class,
                ToolStatusChangesWidget::class,
                ToolStatusChartWidget::class,
                VehicleStatusChartWidget::class,
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
            ->plugins([
                FilamentShieldPlugin::make()
                        ->simpleResourcePermissionView(),
                FilamentUsersPlugin::make(),
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('Mi Perfil')
                    ->setNavigationLabel('Mi Perfil')
                    ->setNavigationGroup('Ajustes')
                    ->setIcon('heroicon-o-user-circle')
                    ->setSort(10)
                    ->shouldShowAvatarForm(true),
                EasyFooterPlugin::make()
                    ->withBorder()
                    ->withLoadTime()
                    ->withLogo(
                        'https://www.boltbitcr.com/_astro/boltbit.DDpEAhKz.png',
                        'https://www.boltbitcr.com',
                        'Desarrollado por ',
                        25
                    ),
                FilamentLoggerPlugin::make(),

            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

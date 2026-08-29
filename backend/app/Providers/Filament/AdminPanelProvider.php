<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Facades\FilamentView;
use Filament\Navigation\NavigationGroup;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('DRIVIO')
            ->favicon(asset('images/logo.png'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\Dashboard\RevenueChart::class,
                \App\Filament\Widgets\Dashboard\StatusDoughnutChart::class,
                \App\Filament\Widgets\Dashboard\UserChart::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Quản lý Phương tiện')
                    ->icon('heroicon-o-truck'),

                NavigationGroup::make()
                    ->label('Truyền thông')
                    ->icon('heroicon-o-document-text'),

                NavigationGroup::make()
                    ->label('Quản lý Vận hành')
                    ->icon(Heroicon::Cog),

                NavigationGroup::make()
                    ->label('Quản lý Người dùng')
                    ->icon('heroicon-o-users')
                    ->collapsible(true),

                NavigationGroup::make()
                    ->label('Cấu hình Hệ thống')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible(true),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::sidebar.footer',
            fn() => view('filament.components.logout-button')
        );
    }
}

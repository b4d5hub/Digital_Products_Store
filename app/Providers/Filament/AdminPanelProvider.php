<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->login()
            ->registration()
            ->profile()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->font(family:'Poppins')
            ->SidebarCollapsibleOnDesktop()
            ->navigationItems([
                NavigationItem::make('Documentation V1')
                    ->url('//localhost', shouldOpenInNewTab:true)
                    ->icon('heroicon-o-link')
                    ->group('Important Links')
                    ->sort(1),
                NavigationItem::make('GitHub')
                    ->url('//github.com/b4d5hub', shouldOpenInNewTab:true)
                    ->icon('heroicon-o-link')
                    ->group('Important Links')
                    ->sort(1),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Store Settings')
                    ->url('/dashboard/store-settings')
                    ->icon('heroicon-o-cog-6-tooth'),
                // MenuItem::make()
                //     ->label('Account Settings')
                //     ->url('')
                //     ->icon('heroicon-o-user'),
                'logout' => MenuItem::make()->label('Log Out')
            ])
            ->breadcrumbs(false)

            //Notifications
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            //Safi SALAW
            
            ->favicon(url:'https://img.icons8.com/?size=512&id=JBCCiDNQwHvt&format=png')
            ->darkMode(condition:true)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ]);
    }
}

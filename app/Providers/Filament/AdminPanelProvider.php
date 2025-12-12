<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        logger('AdminPanelProvider LOADED');

        // 1️⃣ Configure login/auth
        $panel->login()
            ->authGuard('web')
            ->auth(function ($user) {
                
                    return true;
        }); 

        // logger('User email:', [$user->email]);

                // Log the session data to see if it’s being persisted correctly
        logger('Session data:', session()->all());       

        // 2️⃣ Configure other panel settings
        $panel->id('admin')
            ->default()
            ->path('dashboard')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([AccountWidget::class, FilamentInfoWidget::class])
            ->middleware([
                EncryptCookies::class,
    AddQueuedCookiesToResponse::class,
    StartSession::class,
    AuthenticateSession::class,
    Authenticate::class,  // Make sure this is here so the user is authenticated before other middlewares
    ShareErrorsFromSession::class,
    VerifyCsrfToken::class,  // You can uncomment this once you confirm session is working
    SubstituteBindings::class,
    DisableBladeIconComponents::class,
    DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

        return $panel;
    }
}

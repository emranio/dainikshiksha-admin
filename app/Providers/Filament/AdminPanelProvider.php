<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Resources\AuthorResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\NewsResource;
use App\Filament\Resources\TagResource;
use App\Filament\Resources\UserResource;
use App\Http\Middleware\Authenticate;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->spa(
                app()->environment('production')
            )
            ->darkMode(\env('FILAMENT_DARK_MODE', false))
            ->colors([
                'danger' => Color::Rose,
                'gray' => [
                    50 => '239, 240, 240',
                    100 => '200, 205, 203',
                    200 => '229, 231, 235',
                    300 => '209, 213, 219',
                    400 => '156, 163, 175',
                    500 => '107, 114, 128',
                    600 => '75, 85, 99',
                    700 => '55, 65, 81',
                    800 => '31, 41, 55',
                    900 => '17, 24, 39',
                    950 => '3, 7, 18',
                ],
                'info' => Color::Blue,
                'primary' => '#008A4B',
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->default()
            ->id('')
            ->brandName('DainikShiksha')
            ->brandLogo(asset('images/dainikshiksha-logo.png'))
            ->brandLogoHeight('auto')
            ->favicon(asset('favicon.png'))
            ->path('control-panel')
            ->login(Login::class)
            ->resources([
                NewsResource::class,
                AuthorResource::class,
                TagResource::class,
                CategoryResource::class,
                UserResource::class,
            ])
            ->pages([
                \App\Filament\Pages\Auth\Profile::class,
                \App\Filament\Pages\Settings\SiteSettings::class,
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
            ->globalSearch(false)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->plugin(
                FilamentMediaLibrary::make()
                    ->slug('media-library')
                    ->acceptImage()
                    ->acceptPdf()
                    ->mediaPickerModalWidth('8xl')
                    ->unstoredUploadsWarning()
                    // ->mediaInfoOnMultipleSelection()
            )
            ->renderHook(
                'panels::auth.login.form.after',
                fn () => view('auth.socialite.google')
            );
    }
}

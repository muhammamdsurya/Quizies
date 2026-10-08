<?php

namespace App\Providers\Filament;

use App\Notifications\KodeOtpEmail;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Kuiz Digital')
            ->login()
            ->passwordReset()
            ->profile(isSimple: false)
            // OTP via email: aktif otomatis setelah App Password Gmail diisi di .env (MAIL_PASSWORD)
            ->multiFactorAuthentication(
                fn (): array => static::otpEmailAktif()
                    ? [EmailAuthentication::make()->codeNotification(KodeOtpEmail::class)->codeExpiryMinutes(10)]
                    : [],
                isRequired: fn (): bool => static::otpEmailAktif(),
            )
            ->brandLogo(fn () => view('filament.brand'))
            ->brandLogoHeight('2.25rem')
            ->favicon(asset('favicon.png').'?v=2')
            ->sidebarCollapsibleOnDesktop()
            // Pop-up "sesi berakhir" pengganti dialog confirm() bawaan Livewire
            ->renderHook(PanelsRenderHook::BODY_END, fn () => view('partials.sesi-berakhir'))
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
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

    /**
     * OTP email hanya aktif bila email benar-benar terkirim lewat SMTP (App Password Gmail sudah diisi).
     */
    public static function otpEmailAktif(): bool
    {
        return config('mail.default') === 'smtp' && filled(config('mail.mailers.smtp.password'));
    }
}

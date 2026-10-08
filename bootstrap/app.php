<?php

use Filament\Notifications\Notification;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Satu pintu login untuk semua role
        $middleware->redirectGuestsTo(fn () => route('filament.admin.auth.login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // abort(403) dan AuthorizationException sama-sama berujung HttpException 403
        $exceptions->render(function (HttpException $e, Request $request) {
            // Jika akses halaman ditolak, kembalikan ke dashboard dengan pesan (request Livewire/JSON tetap 403)
            if ($e->getStatusCode() !== 403 || ! $request->isMethod('GET') || $request->expectsJson() || $request->is('admin')) {
                return null;
            }

            Notification::make()
                ->title('Anda tidak memiliki akses ke halaman tersebut.')
                ->danger()
                ->send();

            // RedirectResponse langsung: helper redirect() diganti Livewire saat request komponen
            return new RedirectResponse(url('/admin'));
        });
    })->create();

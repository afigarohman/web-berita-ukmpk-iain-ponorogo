<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView; // Tambahan Penting
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Admin LPM Al-Millah')
            
            // 1. MATIKAN DARK MODE
            ->darkMode(false) 

            // 2. SETTING WARNA
            ->colors([
                'primary' => Color::hex('#2d4a53'), // Hijau Gelap
                'warning' => Color::hex('#d4a017'), // Emas
                'danger' => Color::Red,
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'success' => Color::Emerald,
            ])
            
            // 3. SETTING FONT
            ->font('Poppins')

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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

    // KITA PINDAHKAN CSS KUSTOM KE SINI AGAR AMAN
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): string => '
                <style>
                    /* Import Font Poppins */
                    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

                    :root {
                        --font-family: "Poppins", sans-serif;
                    }

                    body {
                        font-family: var(--font-family);
                        background-color: #f8fafc;
                    }

                    /* --- SIDEBAR CUSTOM --- */
                    .fi-sidebar, .fi-sidebar-header {
                        background-color: #2d4a53 !important;
                        border-right: none !important;
                    }

                    /* Teks Menu Putih */
                    .fi-sidebar-item-label, .fi-sidebar-group-label span, .fi-logo {
                        color: #ffffff !important;
                    }
                    
                    /* Icon Menu Emas */
                    .fi-sidebar-item-icon {
                        color: #d4a017 !important;
                    }

                    /* Efek Hover */
                    .fi-sidebar-item:hover {
                        background-color: rgba(255, 255, 255, 0.1) !important;
                        border-radius: 8px;
                    }

                    /* Tombol Utama Emas */
                    .fi-btn-primary {
                        background-color: #d4a017 !important;
                        border: none !important;
                    }
                    
                    /* Rounded Corner untuk Kotak */
                    .fi-section, .fi-wi-stats-overview-stat {
                        border-radius: 16px !important;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
                    }
                </style>
            ',
        );
    }
}
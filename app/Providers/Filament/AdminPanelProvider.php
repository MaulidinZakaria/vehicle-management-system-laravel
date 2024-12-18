<?php

namespace App\Providers\Filament;

use App\Filament\Resources\ApprovalHistoryResource;
use App\Filament\Resources\ApprovalResource;
use App\Filament\Resources\BookingHistoryResource;
use App\Filament\Resources\BookingVehicleResource;
use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\FuelConsumptionResource;
use App\Filament\Resources\LogResource;
use App\Filament\Resources\RentalCompanyResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\VehicleMaintenanceResource;
use App\Filament\Resources\VehicleResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Rmsramos\Activitylog\ActivitylogPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path(path: '')
            ->login()
            ->brandLogo(asset('images/logo.webp'))
            ->brandLogoHeight('3rem')
            ->sidebarCollapsibleOnDesktop()
            ->spa()
            ->favicon(asset('images/logo.png'))
            ->colors([
                'primary' => '#33CDB2',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
            ])->plugins([
                ActivitylogPlugin::make()
                    ->resource(LogResource::class)
                    ->label('Log Aktivitas')
                    ->pluralLabel('Log Aktivitas')
                    ->navigationIcon('heroicon-o-square-3-stack-3d')
                    ->navigationGroup('Log Aktivitas')
            ])->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->items([
                    ...Dashboard::getNavigationItems(),

                ])->groups([
                    NavigationGroup::make(Auth::user()?->level === 'admin' ? 'Kelola Pemesanan' : 'Kelola Persetujuan')->items(
                        array_merge(
                            Auth::user()?->level === 'admin'
                                ? BookingVehicleResource::getNavigationItems()
                                : [],
                            Auth::user()?->level === 'admin'
                                ? BookingHistoryResource::getNavigationItems()
                                : [],
                            ApprovalResource::getNavigationItems(),
                            ApprovalHistoryResource::getNavigationItems()
                        )
                    ),
                    ...(
                        Auth::user()?->level === 'admin'
                        ? [
                            NavigationGroup::make('Kelola Kendaraan')->items([
                                ...VehicleResource::getNavigationItems(),
                                ...FuelConsumptionResource::getNavigationItems(),
                                ...VehicleMaintenanceResource::getNavigationItems(),
                                ...RentalCompanyResource::getNavigationItems(),
                            ])
                        ]
                        : []
                    ),
                    ...(
                        Auth::user()?->level === 'admin'
                        ? [
                            NavigationGroup::make('Kelola Pengguna')->items([
                                ...UserResource::getNavigationItems(),
                                ...EmployeeResource::getNavigationItems(),
                            ])
                        ]
                        : []
                    ),
                    ...(
                        Auth::user()?->level === 'admin'
                        ? [
                            NavigationGroup::make('Log Aktivitas')->items([
                                ...LogResource::getNavigationItems(),
                            ])
                        ]
                        : []
                    ),
                ]);
            });
    }
}

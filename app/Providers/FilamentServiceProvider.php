<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
use Illuminate\Foundation\Vite;
use Illuminate\Support\ServiceProvider;
use Phpsa\FilamentAuthentication\Resources\UserResource;
use Phpsa\FilamentAuthentication\Resources\RoleResource;
use Phpsa\FilamentAuthentication\Resources\PermissionResource;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        Filament::serving(function() {
            Filament::registerViteTheme('resources/css/filament.css');
        });

        // if (auth()->user()) {
        //     if (auth()->user()->is_admin === 1 && auth()->user()->hasAnyRole(['super-admin', 'admin', 'moderator'])) {
        //         Filament::registerUserMenuItems([
        //             UserMenuItem::make()
        //                 ->label('Manage Users')
        //                 ->url(UserResource::getUrl())
        //                 ->icon('heroicon-s-users'),
        //             UserMenuItem::make()
        //                 ->label('Manage Roles')
        //                 ->url(RoleResource::getUrl())
        //                 ->icon('heroicon-s-cog'),
        //             UserMenuItem::make()
        //                 ->label('Manage Permissions')
        //                 ->url(PermissionResource::getUrl())
        //                 ->icon('heroicon-s-key'),
        //         ]);
        //     }
        // }
    }
}

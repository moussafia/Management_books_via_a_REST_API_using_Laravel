<?php

namespace App\Providers;

use Spatie\Permission\Models\Role;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $permissions = Permission::all();

        $adminRole = Role::findByName('admin');
        $adminRole->syncPermissions($permissions);
            app(PermissionRegistrar::class)->registerPermissions();
    }
}

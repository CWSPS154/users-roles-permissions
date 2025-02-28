<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\UsersRolesPermissions;

use App\Models\User;
use CWSPS154\UsersRolesPermissions\Console\Commands\SyncPermissions;
use CWSPS154\UsersRolesPermissions\Database\Seeders\DatabaseSeeder;
use CWSPS154\UsersRolesPermissions\Models\Permission;
use CWSPS154\UsersRolesPermissions\Models\RolePermission;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

class UsersRolesPermissionsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'users-roles-permissions';

    public const HAVE_ACCESS_GATE = 'have-access';

    public function configurePackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations(
                [
                    'alter_user_table',
                    'create_permissions_table',
                    'create_role_permissions_table',
                    'create_roles_table',
                ]
            )
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $command->info('Hi Mate, Thank you for installing Filament Users Roles Permissions.!');
                        if ($command->confirm('Do you want to publish filament import and export migrations')) {
                            $command->comment('Publishing filament action migrations...');
                            $command->call('vendor:publish', ['--tag' => 'filament-actions-migrations']);
                        }
                        if ($command->confirm('Do you want to publish spatie media provider')) {
                            $command->comment('Publishing spatie media provider...');
                            $command->call('vendor:publish', ['--provider' => MediaLibraryServiceProvider::class]);
                            $command->call('storage:link');
                        }
                        $command->comment('Now Please update your User model class with these...');
                        $command->line('`implements HasMedia, HasAvatar, FilamentUser`');
                        $command->line('`use HasRole;`');
                        $command->line("Add these to fillable, ['mobile','role_id','last_seen','is_active']");
                        $command->line("Add these to casts(), ['last_seen' => 'datetime','is_active' => 'boolean']");
                        if (! $command->confirm('Did you update the User model class?')) {
                            $command->error('Please update your User model class otherwise this package will not work!!!');
                        }
                    })
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->endWith(function (InstallCommand $command) {
                        if ($command->confirm('Do you wish to run the seeder ?')) {
                            $command->comment('The seeder is filled with "admin" as panel id, please check the route name for your panel');
                            $command->comment('Running seeder...');
                            $command->call('db:seed', [
                                'class' => DatabaseSeeder::class,
                            ]);
                            $command->comment('You can now access the dashboard with email admin@gmail.com & password admin@123');
                        }
                        $command->info('I hope this package will help you to build user management system');
                        $command->askToStarRepoOnGitHub('CWSPS154/filament-users-roles-permissions');
                    });
            });
    }

    public function boot(): UsersRolesPermissionsServiceProvider
    {
        Gate::define(self::HAVE_ACCESS_GATE, function (User $user, string|array|null $identifiers = null) {
            if ($user->is_admin || ($user->role_id && $user->role->all_permission)) {
                return true;
            }
            $panelId = Filament::getCurrentPanel()->getId();
            if (! is_array($identifiers)) {
                $cacheKey = Permission::$cacheKeyPrefix.'_'.$identifiers.'_'.$panelId;
                $identifiers = explode(',', $identifiers);
            } else {
                $cacheKey = Permission::$cacheKeyPrefix.'_'.implode('_', $identifiers).'_'.$panelId;
            }
            $permissions = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($identifiers, $panelId) {
                return Permission::whereIn('identifier', $identifiers)
                    ->whereJsonContains('panel_ids', $panelId)
                    ->where('status', true)
                    ->select('id', 'panel_ids')
                    ->get();
            });
            Permission::creating(fn () => Cache::forget($cacheKey));
            Permission::updating(fn () => Cache::forget($cacheKey));
            Permission::deleting(fn () => Cache::forget($cacheKey));
            if ($permissions->isNotEmpty()) {
                foreach ($permissions as $permission) {
                    if (RolePermission::where('role_id', $user->role_id)->where('permission_id', $permission->id)->exists()) {
                        return true;
                    }
                }
            }

            return false;
        });
        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncPermissions::class,
            ]);
        }

        return parent::boot();
    }
}

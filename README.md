
# Filament Users Roles Permissions

![Logo](screenshorts/code-with-sps-154-users-roles-permissions.jpg)

Filament User & Roles & Permissions.
## Installation

Install Using Composer

```shell
composer require cwsps154/users-roles-permissions
```
## Usage/Examples

Add this into your Filament `PannelProvider` class `panel()`
```php
use CWSPS154\UsersRolesPermissions\UsersRolesPermissionsPlugin;

$panel->databaseNotifications() //need to see the export files for the permission
    ->databaseTransactions() //optional
    ->plugins([UsersRolesPermissionsPlugin::make()]); //required to enable this extension
```
You can also update UserResource using `setUserResource(UserResource::class)` in the plugin
```php
use CWSPS154\UsersRolesPermissions\UsersRolesPermissionsPlugin;

$panel->plugins([UsersRolesPermissionsPlugin::make()->setUserResource(UserResource::class)]);
```
You can create custom `UserResource` and extend `CWSPS154\UsersRolesPermissions\Filament\Clusters\UserManager\Resources\UserResource as CoreUserResource`

Add the `CWSPS154\UsersRolesPermissions\Models\HasRole` `trait` in `User` Model
```php
use HasRole;
```

And the `User` model should `implements` these `interfaces`'s `Spatie\MediaLibrary\HasMedia`, `Filament\Models\Contracts\HasAvatar` and `Filament\Models\Contracts\FilamentUser`

```php
implements HasMedia, HasAvatar, FilamentUser
```
Also don't forget add these in you User model
```php
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'role_id',
        'last_seen',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
```
Run

```shell
# for laravel 11
php artisan make:queue-batches-table
php artisan make:notifications-table //ensure these queues and notifications migrates are published
# for laravel 10
php artisan queue:batches-table
php artisan notifications:table
# for both
php artisan vendor:publish --tag=filament-actions-migrations //publish filament import and export migrations
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations" //publish spatie media provider
php artisan users-roles-permissions:install
php artisan filament:assets
```

By default, you will get the user which have `email` `admin@gmail.com` & `password` `admin@123`.

Note: For the user which is_admin user have all permission by default.

You can publish the config file `users-roles-permissions.php`, by running this command

```shell
php artisan vendor:publish --tag=users-roles-permissions-config
```
you can create additional permissions using `cwsps-permissions.php` config file.
The updated permissions can sync to database using this command
```shell
php artisan permissions:sync
```

Note:Override may do in random manner for packages, the project config have more priority

In your languages directory, add an extra translation for the mobile field by `propaganistas/laravel-phone`

Note:run this command to publish lang folder
```shell
php artisan lang:publish
```

```php
'phone' => 'The :attribute field must be a valid number.',
```

## Screenshots

![User Roles Permissions Screenshot](screenshorts/user-list.png)

![User Roles Permissions Screenshot](screenshorts/user-create.png)

![User Roles Permissions Screenshot](screenshorts/user-edit.png)

![User Roles Permissions Screenshot](screenshorts/edit-profile.png)

![User Roles Permissions Screenshot](screenshorts/role-list.png)

![User Roles Permissions Screenshot](screenshorts/role-create.png)

![User Roles Permissions Screenshot](screenshorts/role-edit.png)

![User Roles Permissions Screenshot](screenshorts/permission-list.png)

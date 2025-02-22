
# Filament Users Roles Permissions

Filament User & Roles & Permissions.
## Installation

Install Using Composer

```
composer require cwsps154/filament-users-roles-permissions
```
## Usage/Examples

Add this into your Filament `PannelProvider` class `panel()`
```
$panel->databaseNotifications() //need to see the export files for the permission
    ->databaseTransactions() //optional
    ->plugins([FilamentUsersRolesPermissionsPlugin::make()]); //required to enable this extension
```
You can also update UserResource using `setUserResource(UserResource::class)` in the plugin
```
$panel->plugins([FilamentUsersRolesPermissionsPlugin::make()->setUserResource(UserResource::class)]);
```
You can create custom `UserResource` and extend `CWSPS154\FilamentUsersRolesPermissions\Filament\Clusters\UserManager\Resources\UserResource as CoreUserResource`

Add the `CWSPS154\FilamentUsersRolesPermissions\Models\HasRole` `trait` in `User` Model
```
use HasRole;
```

And the `User` model should `implements` these `interfaces`'s `Spatie\MediaLibrary\HasMedia`, `Filament\Models\Contracts\HasAvatar` and `Filament\Models\Contracts\FilamentUser`

```
implements HasMedia, HasAvatar, FilamentUser
```
Also don't forget add these in you User model
```
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

```
php artisan make:queue-batches-table
php artisan make:notifications-table //ensure these queues and notifications migrates are published
php artisan vendor:publish --tag=filament-actions-migrations //publish filament import and export migrations
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations" //publish spatie media provider
php artisan filament-users-roles-permissions:install
```

By default, you will get the user which have `email` `admin@gmail.com` & `password` `admin@123`.

Add this in the `\Filament\Panel\Concerns\HasPlugins::plugins()` method
```
FilamentUsersRolesPermissionsPlugin::make(),
```

Note: For the user which is_admin user have all permission by default.

You can publish the config file `filament-users-roles-permissions.php`, by running this command

```
php artisan vendor:publish --tag=filament-users-roles-permissions-config
```
you can create additional permissions using `cwsps-permissions.php` config file.
The updated permissions can sync to database using this command
```
php artisan permissions:sync
```

Note:Override may do in random manner for packages, the project config have more priority

In your languages directory, add an extra translation for the mobile field by `propaganistas/laravel-phone`

Note:run this command to publish lang folder `php artisan lang:publish`
```
'phone' => 'The :attribute field must be a valid number.',
```

## Screenshots

![App Screenshot](screenshorts/user-list.png)

![App Screenshot](screenshorts/user-create.png)

![App Screenshot](screenshorts/user-edit.png)

![App Screenshot](screenshorts/edit-profile.png)

![App Screenshot](screenshorts/role-list.png)

![App Screenshot](screenshorts/role-create.png)

![App Screenshot](screenshorts/role-edit.png)

![App Screenshot](screenshorts/permission-list.png)

![App Screenshot](screenshorts/permission-create.png)

![App Screenshot](screenshorts/permission-edit.png)

![App Screenshot](screenshorts/permission-edit.png)

![App Screenshot](screenshorts/permission-view.png)

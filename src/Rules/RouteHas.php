<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\UsersRolesPermissions\Rules;

use Closure;
use CWSPS154\UsersRolesPermissions\Models\Permission;
use CWSPS154\UsersRolesPermissions\UsersRolesPermissionsServiceProvider;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Route;
use Illuminate\Translation\PotentiallyTranslatedString;

class RouteHas implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     *
     * @throws Exception
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value) {
            $newValues = [];
            foreach (Filament::getPanels() as $panel) {
                if ($panel->hasPlugin(UsersRolesPermissionsServiceProvider::$name)) {
                    $newValues[] = Permission::FILAMENT_ROUTE_PREFIX.'.'.$panel->getId().'.'.$value;
                }
            }
            if (array_filter($newValues, fn ($newValue) => ! Route::has($newValue))) {
                $fail(__(
                    'users-roles-permissions::users-roles-permissions.permission.validation.unique-route',
                    ['value' => $value]
                ));
            }
        }
    }
}

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
    private array $panel_ids;

    public function __construct(
        ?array $panel_ids = null
    ) {
        $this->panel_ids = $panel_ids;
    }

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
            foreach ($this->panel_ids as $panel_id) {
                $panel = Filament::getPanel($panel_id);
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

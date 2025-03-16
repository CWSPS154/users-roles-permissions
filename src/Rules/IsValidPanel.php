<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\UsersRolesPermissions\Rules;

use Closure;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Contracts\Validation\ValidationRule;

class IsValidPanel implements ValidationRule
{
    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $panel_ids = array_map(fn ($panel) => $panel->getId(), Filament::getPanels());
        if (is_array($value)) {
            if (array_filter($value, fn ($panel_id) => ! in_array($panel_id, $panel_ids))) {
                $fail(__('users-roles-permissions::users-roles-permissions.permission.validation.no-panel-id', ['panel_id' => implode(',', $value)]));
            }
        }
    }
}

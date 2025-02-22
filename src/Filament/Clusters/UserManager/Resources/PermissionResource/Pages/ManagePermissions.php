<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\FilamentUsersRolesPermissions\Filament\Clusters\UserManager\Resources\PermissionResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManagePermissions extends ManageRecords
{
    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament-users-roles-permissions::users-roles-permissions.permission.resource.permission');
    }

    public static function getResource(): string
    {
        return static::$resource = config('filament-users-roles-permissions.resource.permission');
    }
}

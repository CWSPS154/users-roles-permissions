<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\UsersRolesPermissions\Filament\Clusters\UserManager\Resources\RoleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageRoles extends ManageRecords
{
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->slideOver(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('users-roles-permissions::users-roles-permissions.role.resource.role');
    }

    public static function getResource(): string
    {
        return static::$resource = config('users-roles-permissions.resource.role');
    }
}

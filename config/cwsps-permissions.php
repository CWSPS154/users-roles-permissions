<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

use CWSPS154\UsersRolesPermissions\Models\Permission;
use CWSPS154\UsersRolesPermissions\UsersRolesPermissionsServiceProvider;
use Filament\Facades\Filament;

$panel_ids = [];

foreach (Filament::getPanels() as $panel) {
    if ($panel->hasPlugin(UsersRolesPermissionsServiceProvider::$name)) {
        $panel_ids[] = $panel->getId();
    }
}

return [
    Permission::PERMISSION => [
        'name' => 'Permission',
        'panel_ids' => $panel_ids,
        'route' => null,
        'status' => true,
        'children' => [
            Permission::VIEW_PERMISSION => [
                'name' => 'View Permission',
                'panel_ids' => $panel_ids,
                'route' => 'user-manager.resources.permissions.index',
                'status' => true,
            ],
            Permission::CREATE_PERMISSION => [
                'name' => 'Create Permission',
                'panel_ids' => $panel_ids,
                'route' => null,
                'status' => true,
            ],
        ],
    ],
    Permission::ROLE => [
        'name' => 'Role',
        'panel_ids' => $panel_ids,
        'route' => null,
        'status' => true,
        'children' => [
            Permission::VIEW_ROLE => [
                'name' => 'View Role',
                'panel_ids' => $panel_ids,
                'route' => 'user-manager.resources.roles.index',
                'status' => true,
            ],
            Permission::CREATE_ROLE => [
                'name' => 'Create Role',
                'panel_ids' => $panel_ids,
                'route' => null,
                'status' => true,
            ],
            Permission::EDIT_ROLE => [
                'name' => 'Edit Role',
                'panel_ids' => $panel_ids,
                'route' => null,
                'status' => true,
            ],
            Permission::DELETE_ROLE => [
                'name' => 'Delete Role',
                'panel_ids' => $panel_ids,
                'route' => null,
                'status' => true,
            ],
        ],
    ],
    Permission::USER => [
        'name' => 'User',
        'panel_ids' => $panel_ids,
        'route' => null,
        'status' => true,
        'children' => [
            Permission::VIEW_USER => [
                'name' => 'View User',
                'panel_ids' => $panel_ids,
                'route' => 'user-manager.resources.users.index',
                'status' => true,
            ],
            Permission::CREATE_USER => [
                'name' => 'Create User',
                'panel_ids' => $panel_ids,
                'route' => null,
                'status' => true,
            ],
            Permission::EDIT_USER => [
                'name' => 'Edit User',
                'panel_ids' => $panel_ids,
                'route' => null,
                'status' => true,
            ],
            Permission::DELETE_USER => [
                'name' => 'Delete User',
                'panel_ids' => $panel_ids,
                'route' => null,
                'status' => true,
            ],
        ],
    ],
];

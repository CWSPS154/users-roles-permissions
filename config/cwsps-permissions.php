<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

use CWSPS154\FilamentUsersRolesPermissions\Models\Permission;
use Filament\Facades\Filament;

return [
    Permission::PERMISSION => [
        'name' => 'Permission',
        'panel_ids' => [Filament::getDefaultPanel()->getId()],
        'route' => null,
        'status' => true,
        'children' => [
            Permission::VIEW_PERMISSION => [
                'name' => 'View Permission',
                'panel_ids' => [Filament::getDefaultPanel()->getId()],
                'route' => 'user-manager.resources.permissions.index',
                'status' => true,
            ],
            Permission::CREATE_PERMISSION => [
                'name' => 'Create Permission',
                'panel_ids' => [Filament::getDefaultPanel()->getId()],
                'route' => null,
                'status' => true,
            ],
        ],
    ],
    Permission::ROLE => [
        'name' => 'Role',
        'panel_ids' => [Filament::getDefaultPanel()->getId()],
        'route' => null,
        'status' => true,
        'children' => [
            Permission::VIEW_ROLE => [
                'name' => 'View Role',
                'panel_ids' => [Filament::getDefaultPanel()->getId()],
                'route' => 'user-manager.resources.roles.index',
                'status' => true,
            ],
            Permission::CREATE_ROLE => [
                'name' => 'Create Role',
                'panel_ids' => [Filament::getDefaultPanel()->getId()],
                'route' => null,
                'status' => true,
            ],
            Permission::EDIT_ROLE => [
                'name' => 'Edit Role',
                'panel_ids' => [Filament::getDefaultPanel()->getId()],
                'route' => null,
                'status' => true,
            ],
            Permission::DELETE_ROLE => [
                'name' => 'Delete Role',
                'panel_ids' => [Filament::getDefaultPanel()->getId()],
                'route' => null,
                'status' => true,
            ],
        ],
    ],
    Permission::USER => [
        'name' => 'User',
        'panel_ids' => [Filament::getDefaultPanel()->getId()],
        'route' => null,
        'status' => true,
        'children' => [
            Permission::VIEW_USER => [
                'name' => 'View User',
                'panel_ids' => [Filament::getDefaultPanel()->getId()],
                'route' => 'user-manager.resources.users.index',
                'status' => true,
            ],
            Permission::CREATE_USER => [
                'name' => 'Create User',
                'panel_ids' => [Filament::getDefaultPanel()->getId()],
                'route' => null,
                'status' => true,
            ],
            Permission::EDIT_USER => [
                'name' => 'Edit User',
                'panel_ids' => [Filament::getDefaultPanel()->getId()],
                'route' => null,
                'status' => true,
            ],
            Permission::DELETE_USER => [
                'name' => 'Delete User',
                'panel_ids' => [Filament::getDefaultPanel()->getId()],
                'route' => null,
                'status' => true,
            ],
        ],
    ],
];

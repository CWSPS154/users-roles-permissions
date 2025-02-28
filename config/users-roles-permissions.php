<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

use CWSPS154\UsersRolesPermissions\Filament\Clusters\UserManager;
use CWSPS154\UsersRolesPermissions\Filament\Clusters\UserManager\Resources\PermissionResource;
use CWSPS154\UsersRolesPermissions\Filament\Clusters\UserManager\Resources\RoleResource;
use CWSPS154\UsersRolesPermissions\Filament\Clusters\UserManager\Resources\UserResource;
use CWSPS154\UsersRolesPermissions\Filament\Exports\PermissionExporter;
use CWSPS154\UsersRolesPermissions\Filament\Imports\PermissionImporter;

return [
    'cluster' => UserManager::class,
    'resource' => [
        'user' => UserResource::class,
        'role' => RoleResource::class,
        'permission' => PermissionResource::class,
    ],
    'manager' => [
        'user' => 'CWSPS154\UsersRolesPermissions\Filament\Clusters\UserManager\Resources\UserResource\Pages\ManageUsers',
        'role' => 'CWSPS154\UsersRolesPermissions\Filament\Clusters\UserManager\Resources\RoleResource\Pages\ManageRoles',
        'permission' => 'CWSPS154\UsersRolesPermissions\Filament\Clusters\UserManager\Resources\PermissionResource\Pages\ManagePermissions',
    ],
    'export' => [
        'permission' => PermissionExporter::class,
    ],
    'import' => [
        'permission' => PermissionImporter::class,
    ],
];

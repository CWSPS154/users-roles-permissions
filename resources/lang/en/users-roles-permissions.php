<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

return [
    'system' => 'System',
    'user' => [
        'manager' => 'Users Manager',
        'resource' => [
            'user' => 'User',
            'form' => [
                'name' => 'Name',
                'email' => 'Email',
                'mobile' => 'Mobile',
                'role' => 'Role',
                'password' => 'Password',
                'confirm-password' => 'Confirm Password',
                'active' => 'Active',
                'profile-image' => 'Profile Image',
            ],
            'table' => [
                'image' => 'Image',
                'online' => 'Online',
                'verified' => 'Verified',
                'created-at' => 'Created At',
                'updated-at' => 'Updated At',
                'deleted-at' => 'Deleted At',
                'created-by' => 'Created By',
                'updated-by' => 'Updated By',
                'deleted-by' => 'Deleted By',
                'actions' => [
                    'edit-profile' => 'Edit Profile',
                ],
            ],
        ],
        'validation' => [
            'have-access-page' => 'You don\'t have permission to access this page.',
            'is-active' => 'Your account is not active now.',
        ],
    ],
    'role' => [
        'resource' => [
            'role' => 'Role',
            'form' => [
                'name' => 'Role',
                'identifier' => 'Identifier',
                'all-permission' => 'All Permissions',
                'is-active' => 'Is Active',
                'permissions' => 'Permissions',
            ],
            'table' => [
                'created-at' => 'Created At',
                'updated-at' => 'Updated At',
            ],
        ],
    ],
    'permission' => [
        'resource' => [
            'permission' => 'Permission',
            'form' => [
                'name' => 'Name',
                'identifier' => 'Identifier',
                'panel-ids' => 'Panel',
                'children' => 'Children',
                'route' => 'Route',
                'status' => 'Status',
            ],
            'table' => [
                'created-at' => 'Created At',
                'updated-at' => 'Updated At',
            ],
        ],
        'validation' => [
            'unique-route' => 'There no :attribute exist with this :value',
            'no-panel-id' => 'No panel found with id: :panel_id',
        ],
        'console' => [
            'sync-permissions-config-not-found' => 'No :config config files found.',
            'sync-permissions-config-loading' => 'Loading permissions from: :path',
            'sync-permissions-empty' => 'No permissions found in the configuration files.',
            'sync-permissions-completed' => 'Permissions synchronized successfully.',
            'sync-permissions' => 'Syncing permission: :identifier',
            'sync-permission-deleted-permissions' => 'Deleted permissions: :identifiers',
            'sync-permission-invalid-data-format' => 'Invalid permission format. Permission: :permission',
        ],
        'import' => [
            'completed' => 'Your permission import has completed and :successful_rows :row imported.',
            'failed' => ' :failedRowsCount :row failed to import.',
            'helper-text' => [
                'identifier' => 'This will be used to identify the permission in the database. It must be unique.',
                'panel-ids' => 'There should be at least one panel identifier that matches this value, multiple values should be separated by commas',
                'route' => 'There should be at least one route name that matches this value or null value',
                'parent' => 'The parent permission identifier',
            ],
        ],
        'export' => [
            'completed' => 'Your permission export has completed and :successful_rows :row exported.',
            'failed' => ' :failedRowsCount :row failed to export.',
        ],
    ],
];

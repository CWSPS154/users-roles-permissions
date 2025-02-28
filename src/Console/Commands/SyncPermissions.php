<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\UsersRolesPermissions\Console\Commands;

use CWSPS154\UsersRolesPermissions\Models\Permission;
use CWSPS154\UsersRolesPermissions\Rules\RouteHas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SyncPermissions extends Command
{
    const PERMISSIONS_CONFIG = 'cwsps-permissions.php';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions from permissions.php to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $configPaths = $this->getPermissionsConfigPaths();
        if (empty($configPaths)) {
            $this->error(
                __(
                    'users-roles-permissions::users-roles-permissions.permission.console.sync-permissions-config-not-found',
                    ['config' => self::PERMISSIONS_CONFIG]
                )
            );

            return;
        }
        $permissions = [];
        foreach ($configPaths as $path) {
            $this->info(
                __(
                    'users-roles-permissions::users-roles-permissions.permission.console.sync-permissions-config-loading',
                    ['path' => $path]
                )
            );
            $permissions = array_replace_recursive($permissions, require $path);
        }
        if (empty($permissions)) {
            $this->error(__('users-roles-permissions::users-roles-permissions.permission.console.sync-permissions-empty'));

            return;
        }
        $this->syncPermissions($permissions);
        $this->info(__('users-roles-permissions::users-roles-permissions.permission.console.sync-permissions-completed'));
    }

    /**
     * Get all available permissions.php files from project & vendor directories
     */
    private function getPermissionsConfigPaths(): array
    {
        $paths = [];
        $config = self::PERMISSIONS_CONFIG;
        $projectConfigPath = config_path($config);
        if (file_exists($projectConfigPath)) {
            $paths[] = $projectConfigPath;
        }
        $vendorPath = base_path('vendor');
        $configPaths = glob($vendorPath.'/*/*/config/'.$config);

        if (! empty($configPaths)) {
            $paths[] = $configPaths[0];
        }

        return $paths;
    }

    /**
     * Sync permissions from config files into the database
     */
    private function syncPermissions(array $permissions, $parentId = null): void
    {
        $keys = ['name', 'panel_ids', 'route', 'status', 'parent_id', 'children'];
        try {
            foreach ($permissions as $identifier => $permission) {
                if (is_array($permission) && ! empty(array_intersect_key(array_flip($keys), $permission))) {
                    $this->warn(
                        __(
                            'users-roles-permissions::users-roles-permissions.permission.console.sync-permissions',
                            ['identifier' => $identifier]
                        )
                    );
                    $existingPermission = Permission::where('identifier', $identifier)->first();
                    if (! is_array($identifier)) {
                        $permission['identifier'] = $identifier;
                    } else {
                        throw new \Exception(
                            __(
                                'Permission identifier is required. Input: :input',
                                ['input' => json_encode($permission)]
                            )
                        );
                    }
                    $permission['parent_id'] = $parentId;
                    if (isset($permission['panel_ids'])) {
                        $permission['panel_ids'] = array_unique($permission['panel_ids']);
                    }

                    $validationRules = [
                        'name' => 'sometimes|string|max:255',
                        'identifier' => [
                            'required', 'string', 'max:255',
                            Rule::unique('permissions', 'identifier')->ignore($existingPermission?->id),
                        ],
                        'panel_ids' => 'sometimes|array',
                        'route' => [new RouteHas],
                        'status' => 'sometimes|boolean',
                        'parent_id' => [
                            'nullable',
                            Rule::exists('permissions', 'id')->where(fn ($query) => $query->whereNotNull('id')),
                        ],
                    ];

                    $validator = Validator::make($permission, $validationRules);
                    $validatedData = $validator->validate();

                    if ($existingPermission) {
                        $existingPermission->update($validatedData);
                    } else {
                        $existingPermission = Permission::create($validatedData);
                    }

                    if (! empty($permission['children']) && is_array($permission['children'])) {
                        $this->syncPermissions($permission['children'], $existingPermission->id);
                    }
                } else {
                    throw new \Exception(
                        __(
                            'users-roles-permissions::users-roles-permissions.permission.console.sync-permission-invalid-data-format',
                            ['permission' => json_encode($permission)]
                        )
                    );
                }
            }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}

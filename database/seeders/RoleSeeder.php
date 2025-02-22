<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\FilamentUsersRolesPermissions\Database\Seeders;

use CWSPS154\FilamentUsersRolesPermissions\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(
            ['identifier' => Role::ADMIN],
            [
                'role' => 'Admin',
                'all_permission' => true,
                'is_active' => true,
            ]
        );
    }
}

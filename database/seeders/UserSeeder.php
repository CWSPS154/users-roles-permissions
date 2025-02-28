<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\UsersRolesPermissions\Database\Seeders;

use App\Models\User;
use CWSPS154\UsersRolesPermissions\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'admin@gmail.com')
            ->first();

        if ($user) {
            $user->update([
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('admin@123'),
                'is_admin' => true,
                'role_id' => Role::where('identifier', Role::ADMIN)->select('id')->first()->id,
                'is_active' => true,
            ]);
        } else {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'mobile' => '1234567890',
                'email_verified_at' => now(),
                'password' => Hash::make('admin@123'),
                'is_admin' => true,
                'role_id' => Role::where('identifier', Role::ADMIN)->select('id')->first()->id,
                'is_active' => true,
            ]);
        }
    }
}

<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\UsersRolesPermissions\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RolePermission extends Pivot
{
    use HasUuids;

    protected $table = 'role_permissions';

    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];
}

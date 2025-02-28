<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\UsersRolesPermissions\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
{
    use HasUuids;

    public const PERMISSION = 'permission';

    public const VIEW_PERMISSION = 'view-permission';

    public const CREATE_PERMISSION = 'create-permission';

    public const ROLE = 'role';

    public const VIEW_ROLE = 'view-role';

    public const CREATE_ROLE = 'create-role';

    public const EDIT_ROLE = 'edit-role';

    public const DELETE_ROLE = 'delete-role';

    public const USER = 'user';

    public const VIEW_USER = 'view-user';

    public const CREATE_USER = 'create-user';

    public const EDIT_USER = 'edit-user';

    public const DELETE_USER = 'delete-user';

    public const ALL_PERMISSIONS = [
        self::PERMISSION => 'canViewAnyPermission',
        self::VIEW_PERMISSION => 'canViewAnyPermission',
        self::CREATE_PERMISSION => 'canCreatePermission',
        self::ROLE => 'canViewAnyRole',
        self::VIEW_ROLE => 'canViewAnyRole',
        self::CREATE_ROLE => 'canCreateRole',
        self::EDIT_ROLE => 'canEditRole',
        self::DELETE_ROLE => 'canDeleteRole',
        self::USER => 'canViewAnyUser',
        self::VIEW_USER => 'canViewAnyUser',
        self::CREATE_USER => 'canCreateUser',
        self::EDIT_USER => 'canEditUser',
        self::DELETE_USER => 'canDeleteUser',
    ];

    public const FILAMENT_ROUTE_PREFIX = 'filament';

    public static string $cacheKeyPrefix = 'permissions_for_panel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'identifier',
        'panel_ids',
        'route',
        'parent_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'panel_ids' => 'array',
        'status' => 'boolean',
    ];

    protected $appends = ['permission_with_panel_ids'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Permission::class, 'parent_id', 'id');
    }

    public function getRoute()
    {
        return self::FILAMENT_ROUTE_PREFIX.$this->panel_id.'.'.$this->route;
    }

    public function getPermissionWithPanelIdsAttribute()
    {
        if (count(Filament::getPanels()) > 1) {
            return $this->name.' : '.implode(', ', $this->panel_ids);
        }

        return $this->name;
    }
}

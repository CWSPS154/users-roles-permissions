<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\UsersRolesPermissions\Http\Middleware;

use Closure;
use CWSPS154\UsersRolesPermissions\Models\Permission;
use CWSPS154\UsersRolesPermissions\Models\RolePermission;
use Exception;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HaveAccess
{
    /**
     * Handle an incoming request.
     *
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->is_admin || (Auth::user()->role_id && Auth::user()->role->all_permission)) {
                return $next($request);
            }

            $currentRouteName = $request->route()->getName();
            $currentPanelId = Filament::getCurrentPanel()->getId();
            $panelRoutePrefix = Permission::FILAMENT_ROUTE_PREFIX.'.'.$currentPanelId;
            $currentRouteNameWithOutPrefix = str_replace($panelRoutePrefix.'.', '', $currentRouteName);
            $permission = Permission::whereJsonContains('panel_ids', $currentPanelId)
                ->where('route', $currentRouteNameWithOutPrefix)
                ->whereStatus(true)
                ->first();
            if (! $permission) {
                return $next($request);
            }

            $rolePermission = RolePermission::where('role_id', Auth::user()->role_id)
                ->where('permission_id', $permission->id)
                ->first();
            if (! $rolePermission) {
                Notification::make()
                    ->title(__('Warning'))
                    ->body(
                        __('users-roles-permissions::users-roles-permissions.user.validation.have-access-page')
                    )
                    ->warning()
                    ->send();

                return back();
            }
        }

        return $next($request);
    }
}

<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\FilamentUsersRolesPermissions\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class IsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_active) {
            return $next($request);
        } else {
            Session::flush();
            Notification::make()
                ->title(__('Error'))
                ->body(__('filament-users-roles-permissions::users-roles-permissions.user.validation.is-active'))
                ->danger()
                ->send();
            $loginUrl = Filament::getDefaultPanel()->getLoginUrl();
            if (Filament::getCurrentPanel()->getLoginUrl()) {
                $loginUrl = Filament::getCurrentPanel()->getLoginUrl();
            }

            return redirect($loginUrl);
        }
    }
}

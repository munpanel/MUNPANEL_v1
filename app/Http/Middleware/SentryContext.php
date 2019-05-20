<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App\Http\Middleware;

use Closure;

class SentryContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (app()->bound('sentry')) {
            /** @var \Raven_Client $sentry */
            $sentry = app('sentry');

            // Add user context
            $user = $request->user();
            if (is_object($user)) {
                $sentry->user_context(['id' => $user->id, 'rid' => \App\Reg::currentID(), 'username' => $user->name, 'email' => $user->email, 'ip_address' => $request->ip()]);
            } else {
                $sentry->user_context(['id' => null]);
            }

            // Add tags context
            $sentry->tags_context(['conference_id' => \App\Reg::currentConferenceID()]);
        }
        return $next($request);
    }
}

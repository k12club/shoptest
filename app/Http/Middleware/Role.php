<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array $allowedRoles
     * @return mixed
     */
    public function handle($request, Closure $next, $allowedRoles)
    {
        if (auth()->check()) {
            // If this route allows everyone (who logged in)
            if ($allowedRoles == '*') {
                return $next($request);
            }

            $allowedRoles = explode('|', $allowedRoles);

            // If the logged in user has one of the allowed roles
            if (in_array($request->user()->type, $allowedRoles)) {
            //if ($request->user()->hasRole($allowedRoles)) {
                return $next($request);
            } else {
                return redirect('/')->with('alert-warning', 'You are not allowed to access the requested page.');
            }
        } else {
            return $next($request);
        }

    }
}
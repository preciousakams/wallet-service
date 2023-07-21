<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Illuminate\Http\Request;

class RoleManagement
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param  \Closure $next
     * @param  string $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user()->hasRole($role)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_BAD_REQUEST)
                ->withMessage(trans('validation.role_failed'))
                ->build();
        }

        return $next($request);
    }
}

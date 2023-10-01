<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
            ->withHttpCode(Response::HTTP_UNAUTHORIZED)
            ->withMessage('unauthenticated')
            ->build();
    }
}

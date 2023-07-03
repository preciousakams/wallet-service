<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\Exceptions\InvalidIncludeQuery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        //return parent::render($request, $exception);
            if ($exception instanceof AuthorizationException) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_UNAUTHORIZED)
                    ->withMessage($exception->getMessage())
                    ->build();
            }

            if ($exception instanceof HttpException) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_FORBIDDEN)
                    ->withMessage(empty($exception->getMessage()) ?  'Route not found' : $exception->getMessage() )
                    ->build();
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_FORBIDDEN)
                    ->withMessage($exception->getMessage())
                    ->build();
            }

            if ($exception instanceof AuthenticationException) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_FORBIDDEN)
                    ->withMessage('Unauthenticated')
                    ->build();
            }

            if ($exception instanceof ModelNotFoundException) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_NOT_FOUND)
                    ->withMessage('The requested resource does not exist')
                    ->build();
            }

            if ($exception instanceof NotFoundHttpException) {

                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_NOT_FOUND)
                    ->withMessage($exception->getMessage())
                    ->build();
            }

            if ($exception instanceof JWTException) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                    ->withMessage('Token could not be created')
                    ->build();
            }

            if ($exception instanceof TokenExpiredException) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_NOT_ACCEPTABLE)
                    ->withMessage('Token already expired')
                    ->build();
            }

            if ($exception instanceof TokenInvalidException) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_NOT_ACCEPTABLE)
                    ->withMessage('Invalid token')
                    ->build();
            }

            if ($exception instanceof InvalidIncludeQuery) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_NOT_ACCEPTABLE)
                    ->withMessage($exception->getMessage())
                    ->build();
            }

        return parent::render($request, $exception);
    }
}

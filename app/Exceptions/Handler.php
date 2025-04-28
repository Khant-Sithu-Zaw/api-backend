<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {
        // Handle AuthenticationException (token issues)
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Token has expired, please log in again'
            ], 401); // Unauthorized
        }

        // You can add additional exception handling logic here for other types of exceptions.

        return parent::render($request, $exception);
    }
}

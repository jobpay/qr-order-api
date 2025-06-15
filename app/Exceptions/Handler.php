<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
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
        $this->renderable(function (AuthenticationException $e) {
            return response()->json(
                ['errors' => '認証されていません。'],
                Response::HTTP_UNAUTHORIZED
            );
        });

        $this->renderable(function (ValidationException $e) {
            return response()->json(
                data: ['errors' => $e->validator->errors()->all()],
                status: Response::HTTP_BAD_REQUEST
            );
        });

        $this->reportable(function (Throwable $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(
                data: ['errors' => 'サーバーエラーが発生しました。'],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        });
    }
}

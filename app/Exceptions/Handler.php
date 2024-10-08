<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {   //add Accept: application/json in request
            return $this->handleApiException($request, $exception);
        } else {
            // add checking for AuthorizationException
            if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                // send response for unauthorized access
                // return response()->view('errors.403', [], 403);
                return back()->with(["type" => "error", "message" => "akses tidak diijinkan!"]);
            }

            $retval = parent::render($request, $exception);
        }

        return $retval;
    }

    private function handleApiException($request, Exception $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];

        switch ($statusCode) {
            case 401:
                $response['status'] = 'error';
                $response['code'] = $statusCode;
                $response['message'] = 'unauthorized';
                break;
            case 403:
                $response['status'] = 'error';
                $response['code'] = $statusCode;
                $response['message'] = 'forbidden';
                break;
            case 404:
                $response['status'] = 'error';
                $response['code'] = $statusCode;
                $response['message'] = 'not found';
                break;
            case 405:
                $response['status'] = 'error';
                $response['code'] = $statusCode;
                $response['message'] = 'method not allowed';
                break;
            case 422:
                $response['status'] = 'error';
                $response['code'] = $statusCode;
                $response['message'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            default:
                $response['status'] = 'error';
                $response['code'] = $statusCode;
                $response['message'] = ($statusCode == 500) ? 'looks like something went wrong' : $exception->getMessage();
                break;
        }

        if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }

        return response()->json($response, $statusCode);
    }
}

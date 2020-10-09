<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Arr;                                 // Yo chai Arr::get() use garna ko lagi import gareko
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        return parent::render($request, $exception);
    }


    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)                // Handler.php page ma yo code hudaina... but laravel ko ExceptionHandler page ma huxa.. so tya batw copy garera lyako & yo page ma hamilai jasari jun page ma exception dekhauna man chai tesaigari condtion lagayera code lekheko
    {
        if( $request->expectsJson() )
        {
            return response()->json(['message' => $exception->getMessage()], 401);
        }


        // dd( $exception->guards() );                  // yo thau ma exception aauda guard ko value k aai rako cha check garna ko lagi yesto garincha
        //$guard = Array($exception->guards(), 0);
        $guard = Arr::get($exception->guards(),0);
        //dd( $guard );

        $route = '';

        if( $guard == 'admin')
        {
            $route = '/admin/login';
        }
        else if( $guard == 'user' )
        {
            $route = '/login';
        }

        return redirect( $route );


    }
}

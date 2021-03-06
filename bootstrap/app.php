<?php
/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);


/**
|--------------------------------------------------------------------------
| Add the middleware for cross site scripting
|--------------------------------------------------------------------------
|
| In order for a javascript frontend to make calls, the right headers need
| to be set.  This is vendor library that cofigure the headers.
*/
#$app->configure('cors');


/**
 * Configure Monolog.
 */
$app->configureMonologUsing( function( Monolog\Logger $monolog) {
    $processUser = posix_getpwuid( posix_geteuid() );
    $processName= $processUser[ 'name' ];

    $filename = storage_path( 'logs/laravel-' . php_sapi_name() . '-' . $processName . '.log' );
    $handler = new Monolog\Handler\RotatingFileHandler( $filename );
    $monolog->pushHandler( $handler );
});




/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;

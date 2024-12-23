<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------|
| Create The Application                                                   |
|--------------------------------------------------------------------------|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();
$app->withEloquent();

/*
|--------------------------------------------------------------------------|
| Register Container Bindings                                              |
|--------------------------------------------------------------------------|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------|
| Register Config Files                                                    |
|--------------------------------------------------------------------------|
*/

$app->configure('app');
$app->configure('jwt');

/*
|--------------------------------------------------------------------------|
| Register Middleware                                                      |
|--------------------------------------------------------------------------|
*/

$app->routeMiddleware([
    'auth' => Tymon\JWTAuth\Http\Middleware\Authenticate::class, // Gunakan middleware JWT
    'jwt.auth' => Tymon\JWTAuth\Middleware\GetUserFromToken::class, // Middleware untuk mengambil user dari token
    'jwt.refresh' => Tymon\JWTAuth\Middleware\RefreshToken::class, // Middleware untuk refresh token
]);

/*
|--------------------------------------------------------------------------|
| Register Service Providers                                               |
|--------------------------------------------------------------------------|
*/

$app->register(App\Providers\AuthServiceProvider::class);
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);

/*
|--------------------------------------------------------------------------|
| Load The Application Routes                                              |
|--------------------------------------------------------------------------|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
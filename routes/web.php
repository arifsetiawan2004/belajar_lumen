<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->get('hello', function () {
//     return 'Hello Arif!!';
// });

$router->get('email/verify/{id}/{hash}','AuthController@verifyEmail');

$router->group(['middleware' => 'api_key'], function () use ($router){
// autentifikasi

    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->group(['middleware' => 'auth',],
        function () use ($router) {
            $router->post('refresh', 'AuthController@refresh');
            $router->post('me', 'AuthController@me');
    });

    // CRUD

    $router->group(['middleware' => 'auth',],
        function () use ($router){
        $router->get('users', 'UserController@index');
        $router->get('user/{id}', 'UserController@show');
        $router->post('user/create', 'UserController@store');
        $router->put('user/{id}', 'UserController@update');
        $router->delete('user/{id}', 'UserController@delete');
        $router->post('user/{id}/upload', 'UserController@upload');
        $router->post('user/restore/{id}', 'UserController@restore');
    });
});







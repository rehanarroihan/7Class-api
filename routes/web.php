<?php

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

$router->group(['prefix' => 'v1/user'], function () use ($router) {
    $router->post('register', ['uses' => 'UsersController@emailRegistration']);
    $router->post('auth', ['uses' => 'UsersController@emailLogin']);
});

$router->group(['prefix' => 'v1/class'], function () use ($router) {
    $router->group(['middleware' => 'jwt.auth'], function () use ($router) {
        $router->post('', ['uses' => 'ClassesController@store']);
        $router->get('', ['uses' => 'ClassesController@myclasses']);
        $router->get('/{id_class}', ['uses' => 'ClassesController@classdetail']);
        $router->post('/enroll', ['uses' => 'ClassesController@enroll']);
        $router->post('/exit', ['uses' => 'ClassesController@exit']);
    });
});

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

$router->post('/auth', 'AuthController@auth');
$router->get('/me', 'UserController@me');
$router->get('/check', 'UserController@check');

$router->get('/visitor', 'VisitorController@index');
$router->get('/visitor/count', 'VisitorController@count');
$router->post('/visitor/create', 'VisitorController@create');
$router->post('/visitor/manual', 'VisitorController@manual');
$router->post('/visitor/scan/{id}', 'VisitorController@scan');

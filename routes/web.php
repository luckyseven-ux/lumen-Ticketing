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

$router->get('user',function () use ($router) {
    return 'hello-light';
}); 

$router->post('tickets/create', 'TicketController@store');

// Route untuk mendapatkan semua tiket
$router->get('tickets', 'TicketController@index');

// Route untuk mendapatkan detail tiket berdasarkan ID
$router->get('tickets/{id}', 'TicketController@show');

$router->post('/login', 'AuthController@login');
$router->post('/logout', ['middleware' => 'jwt.auth', 'uses' => 'AuthController@logout']);
$router->get('/admin/dashboard', 
            ['middleware' => 'role:admin', 'uses' => 'AdminController@dashboard']);
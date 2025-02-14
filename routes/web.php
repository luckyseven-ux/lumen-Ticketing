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

$router->get('user', function () use ($router) {
    return 'hello-light';
});



// Route untuk mendapatkan semua tiket
$router->post('/login', 'AuthController@login');
$router->post('/logout', ['middleware' => 'jwt.auth', 'uses' => 'AuthController@logout']);
$router->get(
    '/admin/dashboard',
    ['middleware' => 'role:admin', 'uses' => 'AdminController@dashboard']
);

$router->group(['middleware' => 'admin'], function () use ($router) {
    $router->post('/tickets/create', 'TicketController@store'); // Tambah tiket
    $router->put('/tickets/update/{id}', 'TicketController@update'); // Update tiket
    $router->delete('/tickets/{id}', 'TicketController@destroy'); // Hapus tiket
});



$router->group(['prefix' => 'orders', 'middleware' => 'auth'], function () use ($router) {
    $router->get('/', 'OrderController@index'); // Lihat pesanan user
    $router->post('/create', 'OrderController@store'); // Pesan tiket
    $router->put('/{id}/payment', 'OrderController@updatePayment'); // Konfirmasi pembayaran
    $router->delete('/{id}', 'OrderController@cancel'); // Batalkan pesanan
});

$router->group(['prefix'=>'payment','middleware'=>'auth'],function() use($router){
    $router->post('/create','PaymentController@store');
});
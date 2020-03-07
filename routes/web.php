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

// Client
$router->group(['prefix' => 'client'], function () use ($router) {
    $router->get('/', 'ClientController@index');
    $router->post('/', 'ClientController@store');
    $router->get('/{id}', 'ClientController@show');
    $router->get('/{id}/accounts', 'ClientController@listAccounts');
});

// Account
$router->group(['prefix' => 'account'], function () use ($router) {
    $router->get('/', 'AccountController@index');
    $router->post('/', 'AccountController@store');
    $router->get('/{id}', 'AccountController@balance');
    $router->post('/{id}/activate', 'AccountController@activate');
    $router->post('/{id}/deactivate', 'AccountController@deactivate');
    $router->post('/{id}/deposit', 'AccountController@deposit');
    $router->post('/{id}/withdraw', 'AccountController@withdraw');
    $router->get('/{id}/extract', 'AccountController@extract');
    $router->post('/{id}/transfer', 'AccountController@transfer');
});

// Bankslip
$router->group(['prefix' => 'bankslip'], function () use ($router) {
    $router->get('/', 'BankslipController@index');
    $router->post('/', 'BankslipController@store');
    $router->get('/{id}', 'BankslipController@show');
    $router->post('/pay', 'BankslipController@pay');
});
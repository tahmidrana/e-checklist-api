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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('signup', 'AuthController@signup');
    $router->post('login', 'AuthController@login');

    $router->get('me', 'UserController@profile');

    $router->get('checklists', 'ChecklistController@index');
    $router->post('checklists', 'ChecklistController@store');
    $router->get('checklists/{id}', 'ChecklistController@show');
    $router->delete('checklists/{id}', 'ChecklistController@delete');

    $router->post('checklists/{id}/items', 'ChecklistItemController@store');
    $router->delete('checklists/items/{id}', 'ChecklistItemController@delete');
    $router->post('checklists/items/{id}/update-status', 'ChecklistItemController@updateStatus');
});

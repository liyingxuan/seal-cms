<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(["namespace" => "App\Api\Controllers"], function ($api) {
        $api->post('login', 'UserController@login');
        $api->post('register', 'UserController@register');
        $api->get('verification/{token}', 'UserController@verification');

        $api->group(['middleware' => 'auth:api'], function($api){
            $api->get('details', 'UserController@details');
        });
    });
});
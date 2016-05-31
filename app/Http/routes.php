<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Route Partials
|--------------------------------------------------------------------------
*/

$routePartials = [
    'api',
    'auth'
    ];

foreach($routePartials as $partialRoute) {
    require_once(__DIR__.'/Routes/'.$partialRoute.'.route.php');
}



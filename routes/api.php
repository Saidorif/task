<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'middleware' => 'api',
    'namespace' => '\App\Http\Controllers',
], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');

    Route::group(['middleware' => 'jwt.auth'], function(){
        Route::get('dashboard', 'DashboardController@index');
        Route::get('profile','UserController@profile');
        Route::post('change-password','UserController@changePasword');

        //Task
        Route::get('task','TaskController@index');
        Route::post('task/store','TaskController@store');
        Route::post('task/update/{id}','TaskController@update');
        Route::delete('task/destroy/{id}','TaskController@destroy');

        //Region CRUD
        Route::post('region', 'RegionController@index');
        Route::post('region/store', 'RegionController@store');
        Route::get('region/edit/{id}', 'RegionController@edit');
        Route::post('region/update/{id}', 'RegionController@update');
        Route::delete('region/destroy/{id}', 'RegionController@destroy');

        //Area CRUD
        Route::post('area', 'AreaController@index');
        Route::post('area/store', 'AreaController@store');
        Route::get('area/list', 'AreaController@list');
        Route::get('area/edit/{id}', 'AreaController@edit');
        Route::post('area/update/{id}', 'AreaController@update');
        Route::delete('area/destroy/{id}', 'AreaController@destroy');

        //Role model
        Route::get('role', 'RoleController@index');
        Route::post('role/store', 'RoleController@store');
        Route::get('role/list', 'RoleController@list');
        Route::get('role/edit/{id}', 'RoleController@edit');
        Route::post('role/update/{id}', 'RoleController@update');
        Route::delete('role/destroy/{id}', 'RoleController@destroy');

        // Controllers
        Route::get('controller', 'ContsController@index');
        Route::post('controller/find', 'ContsController@find');
        Route::post('controller/store', 'ContsController@store');
        Route::post('controller/update/{id}', 'ContsController@update')->where('id', '[0-9]+');
        Route::get('controller/edit/{id}', 'ContsController@edit')->where('id', '[0-9]+');
        Route::get('all-controller-actions', 'ContsController@allContActions');

        // Actions
        Route::get('action', 'ActionController@index');
        Route::post('action/store', 'ActionController@store');
        Route::post('action/update/{id}', 'ActionController@update')->where('id', '[0-9]+');
        Route::get('action/edit/{id}', 'ActionController@edit')->where('id', '[0-9]+');

        //Permissions
        Route::post('permissions', 'PermissionController@index');
        Route::post('permissions/store/{id}', 'PermissionController@store')->where('id', '[0-9]+');
    });

});

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
        Route::get('user/all','UserController@index');
        Route::get('user/list','UserController@list');
        Route::post('change-password','UserController@changePasword');
        Route::post('user/store','UserController@store');
        Route::post('user/update/{id}','UserController@update');
        Route::get('user/edit/{id}','UserController@edit');

        //Structure
        Route::get('structure/list','PositionController@list');

        //Task
        Route::post('task','TaskController@index');
        Route::get('task/get-totals','TaskController@getTotals');
        Route::post('task/get-by-date','TaskController@getByDate');
        Route::get('task/user','TaskController@userIndex');
        Route::get('task/user/edit/{id}','TaskController@userEdit');
        Route::post('task/store','TaskController@store');
        Route::post('task/update/{id}','TaskController@update');
        Route::get('task/edit/{id}','TaskController@edit');
        Route::delete('task/destroy/{id}','TaskController@destroy');
        Route::post('task/accept/{id}','TaskController@accept');
        Route::post('task/approve/{id}','TaskController@approve');
        Route::post('task/reject/{id}','TaskController@reject');
        Route::post('task/important/{id}','TaskController@important');

        //User tasks
        Route::post('usertask/store','TaskUserItemController@store');
        Route::post('usertask/update/{id}','TaskUserItemController@update');
        Route::post('usertask/accept/{id}','TaskUserItemController@accept');
        Route::post('usertask/approve/{id}','TaskUserItemController@approve');
        Route::post('usertask/reject/{id}','TaskUserItemController@reject');
        Route::get('usertask/edit/{id}','TaskUserItemController@edit');
        Route::delete('usertask/destroy/{id}','TaskUserItemController@destroy');
        Route::get('usertask/read/{id}','TaskUserItemController@read');//read unread status

        //Region CRUD
        Route::post('region', 'RegionController@index');
        Route::post('region/store', 'RegionController@store');
        Route::get('region/edit/{id}', 'RegionController@edit');
        Route::get('region/list', 'RegionController@list');
        Route::post('region/update/{id}', 'RegionController@update');
        Route::delete('region/destroy/{id}', 'RegionController@destroy');

        //Daily job CRUD
        Route::post('dailyjob', 'DailyjobController@index');
        Route::post('dailyjob/store', 'DailyjobController@store');
        Route::get('dailyjob/edit/{id}', 'DailyjobController@edit');
        Route::get('dailyjob/list', 'DailyjobController@list');
        Route::post('dailyjob/update/{id}', 'DailyjobController@update');
        Route::delete('dailyjob/destroy/{id}', 'DailyjobController@destroy');

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

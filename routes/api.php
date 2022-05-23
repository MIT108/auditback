<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\CourierDepartmentController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\PolyticController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/test', [AuthController::class, 'test'])->name('test');



Route::group(['prefix' => 'auth'], function(){
    Route::post('login', [AuthController::class, 'login'])->name('login.user');
});


Route::group(['middleware' => ['auth:sanctum']], function(){

    //auth controllers
    Route::group(['prefix' => 'auth'], function(){
        Route::get('logout', [AuthController::class, 'logout'])->name('logout.user');
    });


    //role routes
    Route::group(['prefix' => 'role'], function(){
        Route::post('create', [RoleController::class, 'create'])->name('create.role'); //create a new user role
        Route::post('update', [RoleController::class, 'update'])->name('update.role'); //update a user role
        Route::get('listAll', [RoleController::class, 'listAll'])->name('listAll.role'); //list all users roles
        Route::get('list', [RoleController::class, 'list'])->name('list.role'); //list all users roles
        Route::get('delete/{id}', [RoleController::class, 'delete'])->name('delete.role'); //delete user role
    });

    //user routes
    Route::group(['prefix' => 'user'], function(){
        Route::post('create', [UserController::class, 'create'])->name('create.user'); //create a new user
        Route::post('update', [UserController::class, 'update'])->name('update.user'); //update a user
        Route::get('list', [UserController::class, 'listAll'])->name('listAll.user'); //list all users
        Route::get('delete/{id}', [UserController::class, 'delete'])->name('delete.user'); //delete user
    });

    //courier routes
    Route::group(['prefix' => 'courier'], function(){
        Route::post('create', [CourierController::class, 'create'])->name('create.courier'); //create courier
        Route::post('update', [CourierController::class, 'update'])->name('update.courier'); //update courier
        Route::get('list', [CourierController::class, 'list'])->name('list.courier'); //list courier
        Route::get('delete/{id}', [CourierController::class, 'delete'])->name('delete.courier'); //delete courier

        //departement dans courier routes
        Route::group(['prefix' => 'departement'], function(){
            Route::post('create', [CourierDepartmentController::class, 'create'])->name('create.courier.departement');
            Route::post('update', [CourierDepartmentController::class, 'update'])->name('update.courier.departement');
            Route::get('list', [CourierDepartmentController::class, 'list'])->name('listAll.courier.departement');
            Route::get('delete/{id}', [CourierDepartmentController::class, 'delete'])->name('delete.courier.departement');
        });
    });



    //departement routes
    Route::group(['prefix' => 'departement'], function(){
        Route::post('create', [DepartementController::class, 'create'])->name('create.departement'); //create a new user departement
        Route::post('update', [DepartementController::class, 'update'])->name('update.departement'); //update a user departement
        Route::get('listAll', [DepartementController::class, 'listAll'])->name('listAll.departement'); //list all users departements
        Route::get('delete/{id}', [DepartementController::class, 'delete'])->name('delete.departement'); //delete user departement


        //polytic routes
        Route::group(['prefix' => 'polytic'], function(){
            Route::post('create', [PolyticController::class, 'create'])->name('create.Polytic'); //create a new user Polytic
            Route::post('update', [PolyticController::class, 'update'])->name('update.Polytic'); //update a user Polytic
            Route::get('listAll/{departement_id}', [PolyticController::class, 'listAll'])->name('listAll.Polytic'); //list all users Polytics
            Route::get('delete/{id}', [PolyticController::class, 'delete'])->name('delete.Polytic'); //delete user Polytic
        });
    });
});

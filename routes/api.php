<?php

use App\Http\Controllers\BoxesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComingController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OutController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UsersModelController;
use App\Http\Controllers\WarehouseController;
use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\UserAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group(['middleware' => [CorsMiddleware::class]], function () {
    // USERS
    Route::get('/users', [UsersModelController::class, 'index']);
    Route::post('/register', [UsersModelController::class, 'register']);
    Route::post('/login', [UsersModelController::class, 'login']);
    Route::post('/createuser', [UsersModelController::class, 'create']);
    Route::delete('/deleteuser/{id}', [UsersModelController::class, 'destroy']);
    Route::put('/updateuser/{id}', [UsersModelController::class, 'update']);

    // COUNTRY
    Route::get('/country', [CountryController::class, 'index']);
    Route::post('/createcountry', [CountryController::class, 'create']);
    Route::put('/editcountry/{id}', [CountryController::class, 'update']);
    Route::delete('/delcountry/{id}', [CountryController::class, 'delete']);

    // CATEGORY
    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/createcategory', [CategoryController::class, 'create']);
    Route::put('/editcategory/{id}', [CategoryController::class, 'update']);
    Route::delete('/delcategory/{id}', [CategoryController::class, 'delete']);

    // MATERIAL
    Route::get('/material', [MaterialController::class, 'index']);
    Route::post('/creatematerial', [MaterialController::class, 'create']);
    Route::put('/editmaterial/{id}', [MaterialController::class, 'update']);
    Route::delete('/delmaterial/{id}', [MaterialController::class, 'delete']);

    // WAREHOUSE
    Route::get('/warehouse', [WarehouseController::class, 'index']);
    Route::post('/createwarehouse', [WarehouseController::class, 'create']);
    Route::put('/editwarehouse/{id}', [WarehouseController::class, 'update']);
    Route::delete('/delwarehouse/{id}', [WarehouseController::class, 'destroy']);

    // Section
    Route::get('/section', [SectionController::class, 'index']);
    Route::post('/createsection', [SectionController::class, 'create']);
    Route::put('/editsection/{id}', [SectionController::class, 'update']);
    Route::delete('/delsection/{id}', [SectionController::class, 'destroy']);

    // BOXES
    Route::get('/boxes', [BoxesController::class, 'index']);
    Route::post('/createbox', [BoxesController::class, 'create']);
    Route::put('/editbox/{id}', [BoxesController::class, 'update']);
    Route::delete('/delbox/{id}', [BoxesController::class, 'destroy']);

    // PRODUCTS
    Route::get('/products', [ProductsController::class, 'index']);
    Route::post('/createproduct', [ProductsController::class, 'create']);
    Route::put('/updateproduct/{id}', [ProductsController::class, 'update']);
    Route::delete('/deleteproduct/{id}', [ProductsController::class, 'destroy']);
    Route::get('/product/{param}', [ProductsController::class, 'search']);

    // PRIXOD
    Route::get('/coming', [ComingController::class, 'index']);
    Route::post('/createcoming', [ComingController::class, 'create']);
    Route::put('/updatecoming/{id}', [ComingController::class, 'update']);
    Route::delete('/deletecoming/{id}', [ComingController::class, 'destroy']);

    // RASXOD
    Route::get('/out', [OutController::class, 'index']);
    Route::post('/createout', [OutController::class, 'create']);
    Route::put('/updateout/{id}', [OutController::class, 'update']);
    Route::delete('/deleteout/{id}', [OutController::class, 'destroy']);
});

Route::group(['middleware' => UserAuthMiddleware::class], function () {});

Route::middleware([UserAuthMiddleware::class])->group(function () {
    Route::get('/mtest', function () {
        return "salomlar";
    });
});

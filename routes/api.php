<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProduitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'
    
], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
    Route::post('register', [AuthController::class,'register']);
    Route::post('updateprofile',[AuthController::class,'update']);
});
Route::group([
    'midlleware' => 'api',
    'prefix' =>'password'
],function(){
    Route::post('reset-password',[AuthController::class , 'resetPassword']);
    Route::post('reset',[AuthController::class , 'reset']);
});
Route::group([
    'midlleware' => 'api',
    'prefix' =>'produit'
],function(){
    Route::post('createProduit',[ProduitController::class,'store']);
    Route::put('updateProduit/{id}',[ProduitController::class,'update']);
    Route::get('indexProduit',[ProduitController::class,'index']);
    Route::get('showProduit/{id}',[ProduitController::class,'show']);
    Route::delete('destroyProduit/{id}',[ProduitController::class,'destroy']);
    Route::get('filterProduit/{id}',[ProduitController::class,'filtrerParGenre']);
});
Route::group([
    'midlleware' => 'api',
    'prefix' =>'category'
],function(){
    Route::post('createcategory',[CategoryController::class,'store']);
    Route::put('updatecategory/{id}',[CategoryController::class,'update']);
    Route::delete('categoryProduit/{id}',[CategoryController::class,'destroy']);
});
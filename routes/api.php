<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MainController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'v1'], function (){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('create-contact', [MainController::class, 'createContact']);
    Route::get('governorates', [MainController::class, 'getGovernorates']);
    Route::get('cities', [MainController::class, 'getCities']);
    Route::get('categories', [MainController::class, 'getCategories']);
    Route::get('articles', [MainController::class, 'getArticles']);
    Route::get('article', [MainController::class, 'getArticle']);
    Route::get('blood-types', [MainController::class, 'getBloodTypes']);
    Route::get('settings', [MainController::class, 'getSettings']);
});

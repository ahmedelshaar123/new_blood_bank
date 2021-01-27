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
    Route::post('new-password', [AuthController::class, 'newPassword']);
    Route::post('create-contact', [MainController::class, 'createContact']);
    Route::get('governorates', [MainController::class, 'getGovernorates']);
    Route::get('cities', [MainController::class, 'getCities']);
    Route::get('categories', [MainController::class, 'getCategories']);
    Route::get('articles', [MainController::class, 'getArticles']);
    Route::get('article', [MainController::class, 'getArticle']);
    Route::get('blood-types', [MainController::class, 'getBloodTypes']);
    Route::get('donation-requests', [MainController::class, 'getDonationRequests']);
    Route::get('donation-request', [MainController::class, 'getDonationRequest']);
    Route::get('settings', [MainController::class, 'getSettings']);

    Route::group(['middleware'=>'auth:client'], function() {
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('register-token', [AuthController::class, 'registerToken']);
        Route::post('remove-token', [AuthController::class, 'removeToken']);
        Route::post('toggle-favourites', [MainController::class, 'toggleFavourites']);
        Route::get('my-favourites', [MainController::class, 'myFavourites']);
    });
});

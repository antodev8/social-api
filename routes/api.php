<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\TagsController;
use App\Http\Controllers\API\UsersController;
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

Route::group([
    'prefix' => 'v1'
], function () {

    // Login route
    Route::post('login', [AuthController::class, 'login']);
    // Logout route
    Route::post('logout', [AuthController::class,'logout']);

    Route::post('/add/social', [App\Http\Controllers\SocialController::class, 'addSocial']);

    Route::get('/socials', [App\Http\Controllers\SocialController::class, 'socials'])->name('socials');


    Route::group([
        'middleware' => ['auth:sanctum']
    ], function () {

        Route::post('socials/{id}/upload-file', [SocialController::class, 'uploadFile']);




        // Resources routes
        Route::apiResources([
            'users' => UsersController::class,
            'socials' => SocialController::class,

        ]);
    });
});


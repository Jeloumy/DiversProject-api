<?php

use App\Http\Controllers\TournoiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'blog'], function (){
    Route::get('/', [\App\Http\Controllers\Controller::class, 'blogIndex']);
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////route authentification //////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////route authentification //////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::group(['prefix'=>'auth'], function (){
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

    Route::group(['middleware'=>'auth:sanctum'], function (){
        Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    });
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// route profile //////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::get('/profile', 'ProfileController@index')->name('profile');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// route tournois /////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::get('/tournoi', [TournoiController::class, 'index']);

Route::group(['middleware'=>'auth:sanctum', 'prefix'=> "tournoi"],function () {
    Route::post('/', [TournoiController::class, 'store']);
    Route::get('/{tournoi}', [TournoiController::class, 'show']);
    Route::put('/{tournoi}', [TournoiController::class, 'update']);
    Route::delete('/{tournoi}', [TournoiController::class, 'destroy']);
});



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////route équipe //////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


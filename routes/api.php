<?php

use App\Http\Controllers\TournoiController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AuthController;
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////route test //////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::group(['prefix'=>'blog'], function (){
    Route::get('/', [\App\Http\Controllers\Controller::class, 'blogIndex']);
});


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////route authentification //////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::group(['prefix'=>'auth'], function (){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::delete('/{user}', [AuthController::class, 'destroy']);

    Route::group(['middleware'=>'auth:sanctum'], function (){
        Route::put('/{user}/1', [AuthController::class, 'updatePassword']);
        Route::put('/{user}/2', [AuthController::class, 'updateProfile']);
        Route::get('/logout', [AuthController::class, 'logout']);
    });
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// route profile //////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::get('/profile', 'ProfileController@index')->name('profile');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// route tournoi /////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::get('/tournoi', [TournoiController::class, 'index']);

Route::group(['middleware'=>'auth:sanctum', 'prefix'=> "tournoi"],function () {
    Route::post('/', [TournoiController::class, 'store']);
    Route::get('/{tournoi}', [TournoiController::class, 'show']);
    Route::put('/{tournoi}', [TournoiController::class, 'update']);
    Route::delete('/{tournoi}', [TournoiController::class, 'destroy']);
    Route::get('search/{searchQuery}', [TournoiController::class, 'search']);
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////route team //////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::get('/team', [TeamController::class, 'index']);

Route::group(['middleware'=>'auth:sanctum', 'prefix'=> "team"],function () {
    Route::post('/', [TeamController::class, 'store']);
    Route::get('/{team}', [TeamController::class, 'show']);
    Route::put('/{team}', [TeamController::class, 'update']);
    Route::delete('/{team}', [TeamController::class, 'destroy']);
    Route::get('search/{searchQuery}', [TeamController::class, 'search']);
});

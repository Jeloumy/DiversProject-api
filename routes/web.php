<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/blog')->name('blog.')->group(function (){
Route::get('/', function (Request $request) {

    return [
        "link" => route('blog.show', ['slug' => 'article', 'id' => 13]),
    ];
})->name('index');

Route::get('/{slug}-{id}', function(string $slug, string $id, Request $request){
    return [
        "slug" => $slug,
        'id' => $id,
        'name'=> $request->input('name'),
    ];
})->where([
    'id'=>'[0-9]+',
    'slug'=>'[a-z0-9\-]+'
])->name('show');
});

# Socialite URLs


// La redirection vers le provider
Route::get("redirect/{provider}", [SocialiteController::class,'redirect'])->name('socialite.redirect');

// Le callback du provider
Route::get("callback/{provider}", [SocialiteController::class,'callback'])->name('socialite.redirect');

<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SeederController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function(){
    route::post('/login',LoginController::class);
    route::post('/logout',LogoutController::class)->middleware('auth:sanctum');
    route::post('/register',RegisterController::class);


 });




//  Route::middleware('auth:sanctum')->group(function () {
//     // Route::post('/users', [UserController::class, 'store']);
//     // Route::get('/users/{id}', [UserController::class, 'show']);
//     // Route::put('/users/{id}', [UserController::class, 'update']);
//     // Route::delete('/users/{id}', [UserController::class, 'destroy']);
// //   route::get('/index', [UserController::class, 'index']);
//     Route::resource('users', UserController::class);
// });

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::resource('users', UserController::class);
// });


Route::group(['middleware' => ['auth:sanctum', 'role:super-admin']], function () {
    Route::resource('users', UserController::class);
    // Route::get('/assign-role', [UserController::class,'assignRoleToUser']);

});




Route::group(['middleware' => ['auth:sanctum', 'role:super-admin']], function () {
    Route::resource('category', CategoryController::class);

});




// Route::get('/seed-roles-permissions', SeederController::class);



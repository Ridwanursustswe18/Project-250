<?php

use App\Http\Controllers\AppoinmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckDoctor;
use Illuminate\Support\Facades\Route;

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
    return ['Laravel' => app()->version()];
});
Route::get('/token', function () {
    return csrf_token();
});
Route::group(['prefix' => '/doctors','middleware'=>['auth','CheckDoctor:doctor']], function () {
    Route::patch('/{doctor}',[DoctorController::class,'update']);
    Route::get('/{doctor}/edit', [DoctorController::class, 'edit']);
    Route::get('/{doctor}/profile', [DoctorController::class, 'index']);
    Route::get('dashboard',function(){
        return 'Hello Dashboard';
    });
});
Route::group(['prefix' => '/users','middleware'=>['auth','CheckUser:user']], function () {
    Route::patch('/{userdetail}', [UserController::class, 'update']);
    Route::get('/{userdetail}/edit', [UserController::class, 'edit']);
    Route::get('/{userdetail}/profile', [UserController::class, 'index']);
    Route::get('/doctorsinfo', [UserController::class, 'show']);
    Route::post('/book',[AppoinmentController::class,'store']);
    Route::get('/appoinments', [AppoinmentController::class, 'index']);

});
require __DIR__.'/auth.php';

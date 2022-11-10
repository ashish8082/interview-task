<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',[loginController::class,"index"]);
Route::post('/login',[loginController::class,"login"]);
Route::post('/add-vehicle',[loginController::class,"addVehicle"]);
Route::get('/dashboard',[loginController::class,"dashboard"])->name('dashboard.index');
Route::post('/user-logout',[loginController::class,"userLogout"]);
Route::post('/delete-vehicle/{id}',[loginController::class,"deleteVehicle"]);
Route::get('/register',[loginController::class,"register"]);
route::post('/register/add',[loginController::class,"registerForm"]);
Route::post('/search-data',[loginController::class,"searchData"]);





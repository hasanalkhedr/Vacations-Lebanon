<?php

use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\Departments\DepartmentController;
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
    return view('welcome');
});
Route::group(['controller' => EmployeeController::class, 'as' => 'employees.'], function () {
    Route::get('/login', 'login')->middleware('guest')->name('login');
    Route::post('/authenticate', 'authenticate')->middleware('guest')->name('authenticate');
    Route::post('/logout', 'logout')->middleware('auth')->name('logout');
    Route::get('/home', 'home')->middleware('auth')->name('home');

    Route::group(['prefix' => 'employees', 'middleware' => 'role:human_resource'], function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/', 'index')->name('index');
        Route::get('/{employee}', 'show')->name('show');
    });
});

Route::group(['middleware' => 'role:human_resource', 'controller' => DepartmentController::class, 'prefix' => 'departments', 'as' => 'departments.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/', 'index')->name('index');
    Route::get('/{department}', 'show')->name('show');
});

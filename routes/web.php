<?php

use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\Departments\DepartmentController;
use \App\Http\Controllers\Leaves\LeaveController;
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
    Route::get('/login', 'login')->middleware('disable_back')->middleware('guest')->name('login');
    Route::post('/authenticate', 'authenticate')->middleware('guest')->name('authenticate');
    Route::post('/logout', 'logout')->middleware('auth')->name('logout');
    Route::get('/home', 'home')->middleware('disable_back')->middleware('auth')->name('home');

    Route::group(['prefix' => 'employees', 'middleware' => 'auth'], function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/', 'index')->name('index');
        Route::get('/{employee}', 'show')->name('show');
        Route::get('/editprofile/{employee}', 'editProfile')->name('editProfile');
        Route::put('/updateprofile/{employee}', 'updateProfile')->name('updateProfile');
        Route::delete('/{employee}', 'destroy')->name('destroy');
    });

    Route::group(['prefix' => 'employees', 'middleware' => 'role:human_resource|employee'], function () {
        Route::get('/editpassword/{employee}', 'editPassword')->name('editPassword');
        Route::put('/updatepassword/{employee}', 'updatePassword')->name('updatePassword');
    });
});

Route::group(['middleware' => 'role:human_resource', 'controller' => DepartmentController::class, 'prefix' => 'departments', 'as' => 'departments.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/', 'index')->name('index');
    Route::get('/{department}', 'show')->name('show');
    Route::get('/edit/{department}', 'edit')->name('edit');
    Route::put('/update/{department}', 'update')->name('update');
    Route::delete('/{department}', 'destroy')->name('destroy');
});

Route::group(['middleware' => 'role:employee|supervisor|human_resource|sg', 'controller' => LeaveController::class, 'prefix' => 'leaves', 'as' => 'leaves.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/', 'index')->name('index');
    Route::get('/show/{leave}', 'show')->name('show');
//    Route::get('/download/{leave}', 'downloadAttachment')->name('downloadAttachment');
});

Route::group(['middleware' => 'role:supervisor|human_resource|sg', 'controller' => LeaveController::class, 'prefix' => 'leaves', 'as' => 'leaves.'], function () {
    Route::post('/accept/{leave}', 'accept')->name('accept');
    Route::post('/reject/{leave}', 'reject')->name('reject');
});


Route::any('{url}', function () {
    return redirect('/home');
})->where('url', '.*');

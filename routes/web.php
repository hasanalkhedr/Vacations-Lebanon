<?php

use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\Departments\DepartmentController;
use \App\Http\Controllers\Leaves\LeaveController;
use \App\Http\Controllers\Overtimes\OvertimeController;
use Illuminate\Support\Facades\Auth;
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
    return redirect()->route('login');
});
Route::group(['controller' => EmployeeController::class, 'as' => 'employees.'], function () {

    Route::group(['prefix' => 'employees', 'middleware' => 'role_custom:human_resource'], function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/editprofile/{employee}', 'editProfile')->name('editProfile');
        Route::put('/updateprofile/{employee}', 'updateProfile')->name('updateProfile');
        Route::get('/editpassword/{employee}', 'editPassword')->name('editPassword');
        Route::put('/updatepassword/{employee}', 'updatePassword')->name('updatePassword');
        Route::delete('/{employee}', 'destroy')->name('destroy');
    });

    Route::group(['prefix' => 'employees', 'middleware' => 'role_custom:supervisor|human_resource|sg'], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{employee}', 'show')->name('show');
    });
});

Route::group(['middleware' => 'role_custom:human_resource', 'controller' => DepartmentController::class, 'prefix' => 'departments', 'as' => 'departments.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{department}', 'edit')->name('edit');
    Route::get('/{department}', 'show')->name('show');
    Route::put('/update/{department}', 'update')->name('update');
    Route::delete('/{department}', 'destroy')->name('destroy');

});

Route::group(['middleware' => 'auth', 'controller' => DepartmentController::class, 'prefix' => 'departments', 'as' => 'departments.'], function () {
    Route::get('/', 'index')->name('index');
});

Route::group(['middleware' => 'role_custom:employee|supervisor|human_resource|sg', 'controller' => LeaveController::class, 'prefix' => 'leaves', 'as' => 'leaves.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/submitted', 'submitted')->name('submitted');
    Route::get('/{leave}', 'show')->name('show');
    Route::post('/destroy/{leave}', 'destroy')->name('destroy');
//    Route::get('/download/{leave}', 'downloadAttachment')->name('downloadAttachment');
});

Route::group(['middleware' => 'role_custom:supervisor|human_resource|sg', 'controller' => LeaveController::class, 'prefix' => 'leaves', 'as' => 'leaves.'], function () {
    Route::post('/accept/{leave}', 'accept')->name('accept');
    Route::post('/reject/{leave}', 'reject')->name('reject');
    Route::get('/', 'index')->name('index');
});


Route::group(['middleware' => 'role_custom:employee|supervisor|human_resource|sg', 'controller' => OvertimeController::class, 'prefix' => 'overtimes', 'as' => 'overtimes.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/submitted', 'submitted')->name('submitted');
    Route::get('/{overtime}', 'show')->name('show');
    Route::post('/destroy/{overtime}', 'destroy')->name('destroy');
});

Route::group(['middleware' => 'role_custom:supervisor|human_resource|sg', 'controller' => OvertimeController::class, 'prefix' => 'overtimes', 'as' => 'overtimes.'], function () {
    Route::post('/accept/{overtime}', 'accept')->name('accept');
    Route::post('/reject/{overtime}', 'reject')->name('reject');
    Route::get('/', 'index')->name('index');
});


Auth::routes();


Route::any('{url}', function () {
    return redirect()->route('leaves.submitted');
})->where('url', '.*');

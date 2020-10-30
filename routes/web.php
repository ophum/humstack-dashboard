<?php

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

Auth::routes([
    'register' => false,
]);

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('table-list', function () {
        return view('pages.table_list');
    })->name('table');

    Route::get('typography', function () {
        return view('pages.typography');
    })->name('typography');

    Route::get('icons', function () {
        return view('pages.icons');
    })->name('icons');

    Route::get('map', function () {
        return view('pages.map');
    })->name('map');

    Route::get('notifications', function () {
        return view('pages.notifications');
    })->name('notifications');

    Route::get('rtl-support', function () {
        return view('pages.language');
    })->name('language');

    Route::get('env', function () {
        return view('pages.env');
    })->name('env');

    Route::group(['prefix' => 'problems'], function () {
        Route::get('', [
            App\Http\Controllers\ProblemsController::class,
            'index',
        ])->name('problems.index');
        Route::get('/create', [
            App\Http\Controllers\ProblemsController::class,
            'create',
        ])->name('problems.create');
        Route::get('/{problem}', [
            App\Http\Controllers\ProblemsController::class,
            'show',
        ])->name('problems.show');
        Route::post('', [
            App\Http\Controllers\ProblemsController::class,
            'store',
        ])->name('problems.store');

        Route::group(['prefix' => '/{problem}/machines'], function () {
            Route::get('/create', [
                App\Http\Controllers\MachinesController::class,
                'create',
            ])->name('problems.machines.create');
            Route::post('', [
                App\Http\Controllers\MachinesController::class,
                'store',
            ])->name('problems.machines.store');
            Route::get('/{machine}', [
                App\Http\Controllers\MachinesController::class,
                'show',
            ])->name('problems.machines.show');
        });

        Route::group(['prefix' => '/{problem}/networks'], function () {
            Route::get('/create', [
                App\Http\Controllers\NetworksController::class,
                'create',
            ])->name('problems.networks.create');
            Route::post('', [
                App\Http\Controllers\NetworksController::class,
                'store',
            ])->name('problems.networks.store');
            Route::get('/{network}', [
                App\Http\Controllers\NetworksController::class,
                'show',
            ])->name('problems.networks.show');
        });
    });

    Route::group(['prefix' => 'teams'], function () {
        Route::get('', [
            App\Http\Controllers\TeamsController::class,
            'index',
        ])->name('teams.index');
        Route::get('/create', [
            App\Http\Controllers\TeamsController::class,
            'create',
        ])->name('teams.create');
        Route::post('', [
            App\Http\Controllers\TeamsController::class,
            'store',
        ])->name('teams.store');
        Route::get('/{team}', [
            App\Http\Controllers\TeamsController::class,
            'show',
        ])->name('teams.show');
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

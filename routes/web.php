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
        Route::get('/{problem}/topo', function (\App\Models\Problem $problem) {
            $machines = $problem->machines()->get();
            $networks = $problem->networks()->get();
            $nodes = [];
            $base = 0;
            foreach ($machines as $m) {
                $nics = [];
                foreach ($m->attachedNics()->orderBy('order', 'asc')->get() as $i => $nic) {
                    $nics[] = [
                        'name' => "eth".$i,
                        'network' => $nic->name,
                        'address' => $nic->pivot->ipv4_address,
                    ];
                }
                $nodes[] = [
                    'id' => $m->id,
                    'label' => $m->name,
                    'type' => 'machine',
                    'nics' => $nics,
                ];
                $base++;
            }
            foreach ($networks as $n) {
                $nodes[] = [
                    'id' => $base + $n->id,
                    'label' => $n->name,
                    'type' => 'network',
                    'cidr' => $n->ipv4_cidr,
                    'vlan' => $n->vlan_id,
                ];
            }

            $links = [];
            foreach ($machines as $m) {
                foreach ($m->attachedNics as $nic) {
                    $links[] = [
                        'source' => $m->id,
                        'target' => $base + $nic->id,
                    ];
                }
            }
            return response()->json([
                'nodes' => $nodes,
                'links' => $links,
            ]);
        });

        Route::get('/create', [
            App\Http\Controllers\ProblemsController::class,
            'create',
        ])->name('problems.create');
        Route::post('/{problem}/delete', [
            App\Http\Controllers\ProblemsController::class,
            'delete',
        ])->name('problems.delete');
        Route::get('/{problem}', [
            App\Http\Controllers\ProblemsController::class,
            'show',
        ])->name('problems.show');
        Route::post('', [
            App\Http\Controllers\ProblemsController::class,
            'store',
        ])->name('problems.store');

        Route::group(['prefix' => '/{problem}/deploys'], function () {
            Route::post('/{team}/virtualmachines/power-on', [
                App\Http\Controllers\DeploysController::class,
                'powerOnVirtualMachines',
            ])->name('problems.deploys.virtualmachines.powerOn');
            Route::post('/deploy/multi', [
                App\Http\Controllers\DeploysController::class,
                'multiDeploy',
            ])->name('problems.deploys.deploy.multi');
            Route::post('/destroy/multi', [
                App\Http\Controllers\DeploysController::class,
                'multiDestroy',
            ])->name('problems.deploys.destroy.multi');
            Route::get('/{team}/show', [
                App\Http\Controllers\DeploysController::class,
                'show',
            ])->name('problems.deploys.show');
            Route::get('/{team}/storages/{storage}', [
                App\Http\Controllers\DeploysController::class,
                'showBlockStorage',
            ])->name('problems.deploys.storages.show');
            Route::post('/{team}/storages/{storage}', [
                App\Http\Controllers\DeploysController::class,
                'toImageBlockStorage',
            ])->name('problems.deploys.storages.to_image');
            Route::post('/{team}/machines/{machine}/ignore', [
                App\Http\Controllers\DeploysController::class,
                'setIgnoreVM',
            ])->name('problems.deploys.set_ignore');
            Route::post('/{team}/machines/{machine}/management', [
                App\Http\Controllers\DeploysController::class,
                'unsetIgnoreVM',
            ])->name('problems.deploys.unset_ignore');
            Route::get('/{team}', [
                App\Http\Controllers\DeploysController::class,
                'create',
            ])->name('problems.deploys.create');
            Route::post('/{team}', [
                App\Http\Controllers\DeploysController::class,
                'store',
            ])->name('problems.deploys.store');
            Route::post('/{team}/deploy', [
                App\Http\Controllers\DeploysController::class,
                'deploy',
            ])->name('problems.deploys.deploy');
            Route::post('/{team}/destroy', [
                App\Http\Controllers\DeploysController::class,
                'destroy',
            ])->name('problems.deploys.destroy');
            Route::post('/{team}/delete_setting', [
                App\Http\Controllers\DeploysController::class,
                'deleteDeploySetting',
            ])->name('problems.deploys.delete_deploy_setting');
            Route::get('', [
                App\Http\Controllers\DeploysController::class,
                'index',
            ])->name('problems.deploys.index');
        });

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
            Route::get('/{machine}', [
                App\Http\Controllers\MachinesController::class,
                'edit',
            ])->name('problems.machines.edit');
            Route::post('/{machine}/delete', [
                App\Http\Controllers\MachinesController::class,
                'destroy',
            ])->name('problems.machines.delete');
            Route::post('/{machine}', [
                App\Http\Controllers\MachinesController::class,
                'update',
            ])->name('problems.machines.update');

            Route::group(['prefix' => '/{machine}/nics'], function () {
                Route::get('', [
                    App\Http\Controllers\MachinesController::class,
                    'nic',
                ])->name('problems.machines.nics');
                Route::post('', [
                    App\Http\Controllers\MachinesController::class,
                    'nicAttach',
                ])->name('problems.machines.nics.attach');
                Route::post('/{network}/detach', [
                    App\Http\Controllers\MachinesController::class,
                    'nicDetach',
                ])->name('problems.machines.nics.detach');
            });

            Route::group(['prefix' => '/{machine}/storages'], function () {
                Route::get('', [
                    App\Http\Controllers\MachinesController::class,
                    'storage',
                ])->name('problems.machines.storages');
                Route::post('', [
                    App\Http\Controllers\MachinesController::class,
                    'storageAttach',
                ])->name('problems.machines.storages.attach');
                Route::post('/{storage}/detach', [
                    App\Http\Controllers\MachinesController::class,
                    'storageDetach',
                ])->name('problems.machines.storages.detach');
            });
        });

        Route::group(['prefix' => '/{problem}/storages'], function () {
            Route::get('/create', [
                App\Http\Controllers\StoragesController::class,
                'create',
            ])->name('problems.storages.create');
            Route::post('', [
                App\Http\Controllers\StoragesController::class,
                'store',
            ])->name('problems.storages.store');
            Route::get('/{storage}', [
                App\Http\Controllers\StoragesController::class,
                'show',
            ])->name('problems.storages.show');
            Route::get('/{storage}', [
                App\Http\Controllers\StoragesController::class,
                'edit',
            ])->name('problems.storages.edit');
            Route::post('/{storage}/delete', [
                App\Http\Controllers\StoragesController::class,
                'destroy',
            ])->name('problems.storages.delete');
            Route::post('/{storage}', [
                App\Http\Controllers\StoragesController::class,
                'update',
            ])->name('problems.storages.update');
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
            Route::get('/{network}', [
                App\Http\Controllers\NetworksController::class,
                'edit',
            ])->name('problems.networks.edit');
            Route::post('/{network}/delete', [
                App\Http\Controllers\NetworksController::class,
                'destroy',
            ])->name('problems.networks.delete');
            Route::post('/{network}', [
                App\Http\Controllers\NetworksController::class,
                'update',
            ])->name('problems.networks.update');
        });
    });

    Route::group(['prefix' => '/images'], function () {
        Route::get('', [
            App\Http\Controllers\ImagesController::class,
            'index',
        ])->name('images');
        Route::get('/create', [
            App\Http\Controllers\ImagesController::class,
            'create',
        ])->name('images.create');
        Route::post('/', [
            App\Http\Controllers\ImagesController::class,
            'store',
        ])->name('images.store');
        Route::get('/{imageName}', [
            App\Http\Controllers\ImagesController::class,
            'show',
        ])->name('images.show');
        Route::post('/{imageName}/untag', [
            App\Http\Controllers\ImagesController::class,
            'untag',
        ])->name('images.untag');
    });

    Route::group(['prefix' => '/image-entities'], function() {
        Route::get('/{id}', [
            App\Http\Controllers\ImageEntitiesController::class,
            'show',
        ])->name('image-entities.show');
        Route::post('/{id}/delete', [
            App\Http\Controllers\ImageEntitiesController::class,
            'destroy',
        ])->name('image-entities.delete');
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
        Route::post('/{team}', [
            App\Http\Controllers\TeamsController::class,
            'delete',
        ])->name('teams.delete');
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

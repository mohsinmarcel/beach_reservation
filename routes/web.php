<?php

use App\Http\Controllers\TenantController;
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

Route::get('test-route', function () {
    $routes = Route::getRoutes();

    $middlewaresToCheck = ['user.guest', 'user.auth', 'tenant.guest', 'tenant.auth'];
    foreach ($routes as $route) {
        $uri = $route->uri();
        $name = $route->getName();
        $middlewares = $route->middleware();
        // Check if any of the specified middlewares are applied
        foreach ($middlewaresToCheck as $mw) {
            if (in_array($mw, $middlewares)) {
                dd($name);
                // Insert into permissions table if not exists
                DB::table('permissions')->updateOrInsert(
                    ['name' => $name ?? $uri], // Use route name if exists, else URI
                    [
                        'route' => $uri,
                        'middleware' => $mw,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $this->info("✅ Added/Updated: {$name} ({$uri}) with {$mw}");
            }
        }
    }
});



Route::group(['middleware' => 'admin.guest'], function () {

});

Route::group(['middleware' => 'admin.auth'], function () {

});

Route::group(['middleware' => 'user.guest'], function () {

});

Route::group(['middleware' => 'user.auth'], function () {

});

Route::group(['middleware' => 'tenant.guest'], function () {
    Route::get('/tenant/login', [TenantController::class, 'tenantLogin'])->name('tenant.login');
    Route::get('/tenant/register', [TenantController::class, 'tenantRegister'])->name('tenant.register');
    Route::post('/tenant/register/process', [TenantController::class, 'tenantRegisterProcess'])->name('tenant.register.process');
    Route::post('/tenant/login/process', [TenantController::class, 'tenantLoginProcess'])->name('tenant.login.process');
});

Route::group(['middleware' => 'tenant.auth'], function () {
    Route::get('/tenant/dashboard', [TenantController::class, 'tenantDashboard'])->name('tenant.dashboard');
    Route::get('/tenant/seats', [TenantController::class, 'tenantSeats'])->name('tenant.seats');
    Route::post('/tenant/seats/store', [TenantController::class, 'tenantSeatsStore'])->name('tenant.seats.store');
    Route::get('/tenant/logout', [TenantController::class, 'tenantLogout'])->name('tenant.logout');
    Route::get('/tenant/users/list', [TenantController::class, 'tenantUsersList'])->name('tenant.users.list');

});


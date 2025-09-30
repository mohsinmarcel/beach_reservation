<?php

use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
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
    return view('user.dashboard');
});


Route::post('/user/reserve/booking', [UserController::class, 'userReserveBookingLogin'])->name('user.reserve.booking.login');
Route::post('/user/login', [UserController::class, 'userLoginProcess'])->name('user.login.process');
Route::get('/user/logout', [UserController::class, 'userLogout'])->name('user.logout');
Route::get('/user/cancellations', [UserController::class, 'userCancellations'])->name('user.cancellations');
Route::get('/user/reminders', [UserController::class, 'userReminders'])->name('user.reminders');
Route::get('/user/bookings', [UserController::class, 'userBookings'])->name('user.bookings');


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
                $groupName = explode('.', $name)[0] ?? null;
                // Insert into permissions table if not exists
                DB::table('permissions')->updateOrInsert(
                    ['name' => $name ?? $uri], // Use route name if exists, else URI
                    [
                        'route' => $uri,
                        'group' => $groupName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

            }
        }
    }
});


Route::group(['middleware' => 'user.guest'], function () {

});

Route::group(['middleware' => 'user.auth'], function () {

});

Route::group(['middleware' => 'admin.guest'], function () {

});

Route::group(['middleware' => 'admin.auth'], function () {

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
    Route::get('/tenant/users/create', [TenantController::class, 'tenantUsersCreate'])->name('tenant.users.create');
    Route::post('/tenant/users/create/process', [TenantController::class, 'tenantUsersCreateProcess'])->name('tenant.users.create.process');
});


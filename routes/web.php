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

Route::group(['middleware' => 'admin.guest'], function () {

});

Route::group(['middleware' => 'admin.auth'], function () {

});

Route::group(['middleware' => 'user.guest'], function () {

});

Route::group(['middleware' => 'user.auth'], function () {

});

Route::group(['middleware' => 'tenant.guest'], function () {

});

Route::group(['middleware' => 'tenant.auth'], function () {

});

Route::get('/tenant/login', [TenantController::class, 'tenantLogin'])->name('tenant.login');
Route::get('/tenant/register', [TenantController::class, 'tenantRegister'])->name('tenant.register');
Route::post('/tenant/register/process', [TenantController::class, 'tenantRegisterProcess'])->name('tenant.register.process');

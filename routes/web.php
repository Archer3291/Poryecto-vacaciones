<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;
use App\Http\Controllers\Vacations\VacationsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::get('/dashboard', function () {
    return view('dashboard', [
        'users' => User::paginate(10),
    ]);
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('asing_vacation', function () {
    return view('asing_vacation', [
        'users' => User::paginate(10),
    ]);
})
    ->middleware(['auth'])
    ->name('assign');

Route::resource('/users', UserController::class)
    ->only(['index', 'edit', 'update'])
    ->middleware(['auth', 'verified'])
    ->names('admin.users');

Route::get('/details/show/{id}', [VacationsController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('detail.user.show');

Route::post('/details/vacations/{id}', [VacationsController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('vacations.update');

Route::post('/request_vacations', [VacationsController::class, 'sendMail'])
    ->middleware(['auth', 'verified'])
    ->name('vacations.mail');

Route::get('/support', [VacationsController::class, 'getUserRoles'])
    ->middleware(['auth'])
    ->name('support');

require __DIR__ . '/auth.php';

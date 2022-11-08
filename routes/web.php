<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataUserController;
use App\Http\Controllers\Operasi\CalendarController;
use App\Http\Controllers\Operasi\TodoListController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::group(['middleware' => ['auth', 'role:admin']], function(){
    Route::prefix('admin')->group(function(){
        Route::prefix('dashboard')->group(function(){
            Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        });

        Route::prefix('data-user')->group(function(){
            Route::get('/', [DataUserController::class, 'index'])->name('admin.data-user');
            Route::get('/create-user', [DataUserController::class, 'create'])->name('admin.data-user-create');
            Route::post('/create-user', [DataUserController::class, 'store'])->name('admin.data-user-store');
            Route::get('/{id}/edit', [DataUserController::class, 'edit'])->name('admin.data-user-edit');
            Route::put('/{id}/update', [DataUserController::class, 'update'])->name('admin.data-user-update');
            Route::get('/{id}/show', [DataUserController::class, 'show'])->name('admin.data-user-show');
            Route::delete('/{id}/delete', [DataUserController::class, 'destroy'])->name('admin.data-user-delete');
        });
    });
});

Route::group(['middleware' => ['auth', 'role:user']], function(){
    Route::prefix('user')->group(function(){
        Route::prefix('dashboard')->group(function(){
            Route::get('/', [UserDashboardController::class, 'index'])->name('user.dashboard');
        });

        Route::prefix('profile')->group(function(){
            Route::get('/', [UserProfileController::class, 'index'])->name('user.profile');
            Route::get('/edit', [UserProfileController::class, 'edit'])->name('user.profile-edit');
            Route::post('/update', [UserProfileController::class, 'update'])->name('user.profile-update');
        });
    });
});

// operasi
Route::prefix('operasi')->group(function () {
    Route::prefix('todo-list')->group(function () {
        Route::get('/', [TodoListController::class, 'show'])->name('operasi.todoList.show');
        Route::post('/tambah', [TodoListController::class, 'tambah'])->name('operasi.todoList.tambah');
        Route::post('/edit', [TodoListController::class, 'edit'])->name('operasi.todoList.edit');
        Route::get('/jumlah-halaman', [TodoListController::class, 'jumlahHalaman'])->name('operasi.todoList.jumlahHalaman');
        Route::get('/{offset}', [TodoListController::class, 'todoList'])->name('operasi.todoList');
        Route::get('/{id}/edit/{status}', [TodoListController::class, 'check'])->name('operasi.todoList.check');
        Route::post('/{id}/hapus', [TodoListController::class, 'hapus'])->name('operasi.todoList.hapus');
    });

    Route::prefix('calendar')->group(function () {
        Route::get('/', [CalendarController::class, 'show'])->name('operasi.calendar');
        Route::post('/tambah', [CalendarController::class, 'tambah'])->name('operasi.calendar.tambah');
        Route::post('/{id}/edit', [CalendarController::class, 'edit'])->name('operasi.calendar.edit');
        Route::delete('/{id}/hapus', [CalendarController::class, 'hapus'])->name('operasi.calendar.hapus');
    });

});

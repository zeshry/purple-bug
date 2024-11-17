<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

//Navigation
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    //User API
    Route::get('/users', function () {
        return Inertia::render('Users/index');
    })->name('users');

    Route::get('/users/roles', function () {
        return Inertia::render('Users/Roles');
    })->name('roles');

    Route::get('/show-users', [UserController::class, 'index']);
    Route::post('/add-users', [UserController::class, 'store']);
    Route::post('/update-user/{id}', [UserController::class, 'update']);

    Route::get('/show-roles', [UserController::class, 'showRoles']);
    Route::post('/add-roles', [UserController::class, 'storeRoles']);
    Route::post('/update-role/{id}', [UserController::class, 'updateRoles']);
    Route::delete('/delete-role/{id}', [UserController::class, 'deleteRoles']);

    //Expenses API
    Route::get('/expenses', function () {
        return Inertia::render('Expenses/index');
    })->name('expense');

    Route::get('/expenses/category', function () {
        return Inertia::render('Expenses/Categories');
    })->name('expense-category');

    Route::get('/show-expense', [ExpenseController::class, 'showExpense']);
    Route::post('/add-expense', [ExpenseController::class, 'storeExpense']);
    Route::post('/update-expense/{id}', [ExpenseController::class, 'updateExpense']);
    Route::delete('/delete-expense/{id}', [ExpenseController::class, 'deleteExpense']);

    Route::get('/show-expense-categories', [ExpenseController::class, 'showCategories']);
    Route::post('/expense-categories', [ExpenseController::class, 'storeCategory'])->name('expense-categories.store');
    Route::post('/update-categories/{id}', [ExpenseController::class, 'updateCategory'])->name('expense-categories.update');
    Route::delete('/delete-expense-category/{id}', [ExpenseController::class, 'deleteCategory']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

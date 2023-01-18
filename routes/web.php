<?php

use App\Http\Controllers\ProfileController;
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
    return view('home');
});


Route::middleware('auth', 'verified')->group(function () {
    Route::get('/u/account', [ProfileController::class, 'edit'])->name('account.index');
    Route::patch('/u/account', [ProfileController::class, 'update'])->name('account.update');
    Route::delete('/u/account', [ProfileController::class, 'destroy'])->name('account.destroy');
});

// User profile
Route::get('/u/{username}', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/u/', function(){
    return redirect('/'); // Redirect home if user go to /u/
});

require __DIR__.'/admin.php';

require __DIR__.'/auth.php';

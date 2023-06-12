<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebController;
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

Route::get('/', [WebController::class, 'index'])->name('web.index');

Route::get('/template', function(){
    return view('template');
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

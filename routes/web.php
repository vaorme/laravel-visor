<?php

use App\Http\Controllers\MangaDetailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortcutsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\web\ViewerChapter;
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

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/u/account', [ProfileController::class, 'edit'])->name('account.index');
    Route::patch('/u/account', [ProfileController::class, 'update'])->name('account.update');
    Route::delete('/u/account', [ProfileController::class, 'destroy'])->name('account.destroy');

    // :USER SHORTCUTS
    Route::post('shortcut/add', [ShortcutsController::class, 'store'])->name('shortcut.store');

    // :USER UNFOLLOW/FOLLOW MANGA
    Route::post('/u/follow/{mangaid}', [UserController::class, 'followManga'])->name('follow_manga.store');
    Route::post('/u/unfollow/{mangaid}', [UserController::class, 'unfollowManga'])->name('unfollow_manga.store');

    // :USER VIEW/UNVIEW MANGA
    Route::post('/u/view/{mangaid}', [UserController::class, 'viewManga'])->name('view_manga.store');
    Route::post('/u/unview/{mangaid}', [UserController::class, 'unviewManga'])->name('unview_manga.store');

    // :USER FAV/UNFAV MANGA
    Route::post('/u/fav/{mangaid}', [UserController::class, 'favManga'])->name('fav_manga.store');
    Route::post('/u/unfav/{mangaid}', [UserController::class, 'unfavManga'])->name('unfav_manga.store');

    // :USER VIEW/UNVIEW CHAPTER
    Route::post('/u/view_chapter/{mangaid}', [UserController::class, 'viewChapter'])->name('view_chapter.store');
    Route::post('/u/unview_chapter/{mangaid}', [UserController::class, 'unviewChapter'])->name('unview_chapter.store');

    // :SHORTCUTE REMOVE
    Route::post('/u/remove_shortcut', [ShortcutsController::class, 'destroy'])->name('shortcut.destroy');
    
});

// :USER PROFILE
Route::get('/u/{username}/{page?}', [ProfileController::class, 'index'])->where(['page' => 'siguiendo|favoritos|atajos'])->name('profile.index');
Route::get('/u/', function(){
    return redirect('/'); // Redirect home if user go to /u/
});

// :MANGA URLs

Route::prefix('m')->group(function(){
    Route::get('/', function(){
        return redirect('/'); // Redirect home if user go to /u/
    });
    Route::get('{slug}', [MangaDetailController::class, 'index'])->name('manga_detail.index');
});

// :VIEWER CHAPTER
Route::prefix('v')->group(function(){
    Route::get('/', function(){
        return redirect('/'); // Redirect home if user go to /u/
    });
    Route::get('{manga_slug}/{chapter_slug}/{reader_type?}/{current_page?}', [ViewerChapter::class, 'index'])
    ->where(['reader_type' => 'paginado', 'current_page' => '[0-9]+'])
    ->name('chapter_viewer.index'); 
});


require __DIR__.'/admin.php';

require __DIR__.'/auth.php';

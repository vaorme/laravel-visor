<?php

use App\Http\Controllers\web\ComicDetailController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortcutsController;
use App\Http\Controllers\web\LibraryController;
use App\Http\Controllers\web\MembersController;
use App\Http\Controllers\web\ShopController;
use App\Http\Controllers\web\ViewerChapter;
use App\Http\Controllers\web\WebUserController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\WebHooksController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
    Route::get('/u/cuenta', [ProfileController::class, 'edit'])->name('account.index');
    Route::patch('/u/cuenta', [ProfileController::class, 'update'])->name('account.update');
    Route::delete('/u/cuenta', [ProfileController::class, 'destroy'])->name('account.destroy');

    Route::get('/u/compras', [ProfileController::class, 'shopping'])->name('my_shopping.index');

    // :USERS
    Route::prefix('users')->group(function(){

        // :VALIDATE USER AVATAR
        Route::post('validate-avatar', [ProfileController::class, 'validateAvatar'])->name('validateUserAvatar');

        // :UPDATE USER
        Route::patch('{id}', [WebUserController::class, 'update'])->name('currentUser.update');

        // :USER BUY CHAPTER
        Route::post('buy-chapter', [WebUserController::class, 'buyChapter'])->name('userBuyChapter.store');
    });
    // :USER SHORTCUTS
    Route::post('shortcut/add', [ShortcutsController::class, 'store'])->name('shortcut.store');

    // :USER UNFOLLOW/FOLLOW MANGA
    Route::post('/u/follow/{mangaid}', [WebUserController::class, 'followManga'])->name('follow_manga.store');
    Route::post('/u/unfollow/{mangaid}', [WebUserController::class, 'unfollowManga'])->name('unfollow_manga.store');

    // :USER VIEW/UNVIEW MANGA
    Route::post('/u/view/{mangaid}', [WebUserController::class, 'viewManga'])->name('view_manga.store');
    Route::post('/u/unview/{mangaid}', [WebUserController::class, 'unviewManga'])->name('unview_manga.store');

    // :USER FAV/UNFAV MANGA
    Route::post('/u/fav/{mangaid}', [WebUserController::class, 'favManga'])->name('fav_manga.store');
    Route::post('/u/unfav/{mangaid}', [WebUserController::class, 'unfavManga'])->name('unfav_manga.store');

    // :USER VIEW/UNVIEW CHAPTER
    Route::post('/u/view_chapter/{mangaid}', [WebUserController::class, 'viewChapter'])->name('view_chapter.store');
    Route::post('/u/unview_chapter/{mangaid}', [WebUserController::class, 'unviewChapter'])->name('unview_chapter.store');

    // :SHORTCUTE REMOVE
    Route::post('/u/remove_shortcut', [ShortcutsController::class, 'destroy'])->name('shortcut.destroy');
    
    Route::post('/rate/{manga_id}', [WebUserController::class, 'rateManga'])->name('rate_manga.store');

    Route::controller(PaymentController::class)->prefix('checkout')->group(function () {
        Route::get('/', 'index')->name('checkout.index');
        Route::get('/order', 'order')->name('checkout.order');
        Route::get('/cancelled', 'cancelled')->name('checkout.cancelled');
        Route::post('/payment-processing', [PaymentController::class, 'paypalProcessing'])->name('paypal.processing');
        Route::get('/payment-success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');
        Route::get('/payment-cancelled', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');
    });
});

// :WEBHOOK PAYPAL
Route::post('/checkout/paypal/webhook', [WebHooksController::class, 'paypalWebhook']);

// :SHOP
Route::get("/tienda", [ShopController::class, 'index'])->name('shop.index');

// :MEMBERS
Route::get('/usuarios', [MembersController::class, 'index'])->name('members.index');

// :USER PROFILE
Route::get('/u/{username}/{item?}', [ProfileController::class, 'index'])->where(['item' => 'siguiendo|favoritos|atajos'])->name('profile.index');

Route::get('/u/', function(){
    return redirect('/usuarios'); // Redirect home if user go to /u/
});

// :UPDATES
Route::get("/actualizaciones", [WebController::class, 'updates'])->name('updates.index');

// :MANGA DETAIL

Route::prefix('biblioteca')->group(function(){
    Route::get('/', [LibraryController::class, 'index'])->name('library.index');
});

// :MANGA DETAIL
Route::prefix('l')->group(function(){
    Route::get('/', function(){
        return redirect('/biblioteca'); // Redirect home if user go to /u/
    });
    Route::get('{slug}', [ComicDetailController::class, 'index'])->name('comic_detail.index');
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

// ?: PAGES
Route::get("/politicas-privacidad", [PagesController::class, 'policies'])->name('privacy_policies.index');

require __DIR__.'/dashboard.php';

require __DIR__.'/auth.php';
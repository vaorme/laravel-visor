<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Dashboard\ChaptersController;
use App\Http\Controllers\MangaBookStatusController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\MangaDemographyController;
use App\Http\Controllers\MangaTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\uploadChaptersController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:developer|administrador|moderador'])->group(function () {

    Route::prefix('controller')->group(function(){
        Route::get('/', function(){
            return view('admin.index');    
        })->name('admin.index');

        Route::prefix('manga')->group(function(){

            /*/ MANGA DEMOGRAPHY /*/
            Route::get('demography', [MangaDemographyController::class, 'index'])->middleware(['permission:manga_demography.index'])->name('manga_demography.index');

            // Create
            Route::get('demography/create', [MangaDemographyController::class, 'create'])->middleware(['permission:manga_demography.create'])->name('manga_demography.create');
            Route::post('demography', [MangaDemographyController::class, 'store'])->middleware(['permission:manga_demography.create'])->name('manga_demography.store');

            //Edit
            Route::get('demography/{id}', [MangaDemographyController::class, 'edit'])->middleware(['permission:manga_demography.edit'])->name('manga_demography.edit');
            Route::patch('demography/{id}', [MangaDemographyController::class, 'update'])->middleware(['permission:manga_demography.edit'])->name('manga_demography.update');

            // Delete
            Route::delete('demography/{id}', [MangaDemographyController::class, 'destroy'])->middleware(['permission:manga_demography.destroy'])->name('manga_demography.destroy');

            /*/ MANGA TYPE /*/
            Route::get('type', [MangaTypeController::class, 'index'])->middleware(['permission:manga_types.index'])->name('manga_types.index');

            // Create
            Route::get('type/create', [MangaTypeController::class, 'create'])->middleware(['permission:manga_types.create'])->name('manga_types.create');
            Route::post('type', [MangaTypeController::class, 'store'])->middleware(['permission:manga_types.create'])->name('manga_types.store');

            // Edit
            Route::get('type/{id}', [MangaTypeController::class, 'edit'])->middleware(['permission:manga_types.edit'])->name('manga_types.edit');
            Route::patch('type/{id}', [MangaTypeController::class, 'update'])->middleware(['permission:manga_types.edit'])->name('manga_types.update');

            // Delete
            Route::delete('type/{id}', [MangaTypeController::class, 'destroy'])->middleware(['permission:manga_types.destroy'])->name('manga_types.destroy');

            /*/ MANGA BOOK STATUS /*/
            Route::get('status', [MangaBookStatusController::class, 'index'])->middleware(['permission:manga_book_status.index'])->name('manga_book_status.index');

            // Create
            Route::get('status/create', [MangaBookStatusController::class, 'create'])->middleware(['permission:manga_book_status.create'])->name('manga_book_status.create');
            Route::post('status', [MangaBookStatusController::class, 'store'])->middleware(['permission:manga_book_status.create'])->name('manga_book_status.store');

            // Edit
            Route::get('status/{id}', [MangaBookStatusController::class, 'edit'])->middleware(['permission:manga_book_status.edit'])->name('manga_book_status.edit');
            Route::patch('status/{id}', [MangaBookStatusController::class, 'update'])->middleware(['permission:manga_book_status.edit'])->name('manga_book_status.update');

            // Delete
            Route::delete('status/{id}', [MangaBookStatusController::class, 'destroy'])->middleware(['permission:manga_book_status.destroy'])->name('manga_book_status.destroy');

            /*/ MANGA /*/
            Route::get('/', [MangaController::class, 'index'])->middleware(['permission:manga.index'])->name('manga.index');
            
            // Create
            Route::get('create', [MangaController::class, 'create'])->middleware(['permission:manga.create'])->name('manga.create');
            Route::post('/', [MangaController::class, 'store'])->middleware(['permission:manga.create'])->name('manga.store');

            // Edit
            Route::get('{id}', [MangaController::class, 'edit'])->middleware(['permission:manga.edit'])->name('manga.edit');
            Route::patch('{id}', [MangaController::class, 'update'])->middleware(['permission:manga.edit'])->name('manga.update');

            // Delete
            Route::delete('{id}', [MangaController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('manga.destroy');
        });

        /* MANGA CHAPTERS */
        Route::prefix('chapters')->group(function(){
            Route::post('/create-chapter/{mangaid}', [ChaptersController::class, 'createChapter'])->middleware(['permission:chapters.create'])->name('createChapter.store');
            Route::post('/upload-single-image', [uploadChaptersController::class, 'uploadSingleImage'])->middleware(['permission:chapters.create'])->name('uploadSingle.store');

            Route::post('uploadChapter/{mangaid}', [uploadChaptersController::class, 'store'])->middleware(['permission:chapters.store'])->name('uploadChapter.store');

            Route::post('getChapter/{mangaid}', [ChaptersController::class, 'show'])->middleware(['permission:chapters.index'])->name('chapters.show');

            Route::post('/{mangaid}', [ChaptersController::class, 'store'])->middleware(['permission:chapters.create'])->name('chapters.store');
            
            Route::post('uploadChapterImages/{mangaid}', [uploadChaptersController::class, 'subirImagenes'])->middleware(['permission:chapters.create'])->name('subirChapter.imagenes');

            Route::post('updateChapterOrder/{chapterid}', [uploadChaptersController::class, 'actualizarOrdenImagenes'])->middleware(['permission:chapters.store'])->name('actualizarOrdenChapter.imagenes');

            Route::post('deleteChapterImage/{chapterid}', [uploadChaptersController::class, 'eliminarImagen'])->middleware(['permission:chapters.destroy'])->name('eliminarChapter.imagenes');

            Route::patch('/{chapterid}', [ChaptersController::class, 'update'])->middleware(['permission:chapters.update'])->name('chapters.update');

            Route::delete('/{chapterid}', [ChaptersController::class, 'destroy'])->middleware(['permission:chapters.destroy'])->name('chapters.destroy');
        });

        Route::prefix('categories')->group(function(){
            Route::get("/", [CategoryController::class, 'index'])->middleware(['permission:categories.index'])->name('categories.index');
            
            // Create
            Route::get('create', [CategoryController::class, 'create'])->middleware(['permission:categories.create'])->name('categories.create');
            Route::post('/', [CategoryController::class, 'store'])->middleware(['permission:categories.create'])->name('categories.store');

            //Edit
            Route::get('{id}', [CategoryController::class, 'edit'])->middleware(['permission:categories.edit'])->name('categories.edit');
            Route::patch('{id}', [CategoryController::class, 'update'])->middleware(['permission:categories.edit'])->name('categories.update');

            // Delete
            Route::delete('{id}', [CategoryController::class, 'destroy'])->middleware(['permission:categories.destroy'])->name('categories.destroy');
        });

        Route::prefix('tags')->group(function(){
            Route::get("/", [TagController::class, 'index'])->middleware(['permission:tags.index'])->name('tags.index');
            
            // Create
            Route::get('create', [TagController::class, 'create'])->middleware(['permission:tags.create'])->name('tags.create');
            Route::post('/', [TagController::class, 'store'])->middleware(['permission:tags.create'])->name('tags.store');

            //Edit
            Route::get('{id}', [TagController::class, 'edit'])->middleware(['permission:tags.edit'])->name('tags.edit');
            Route::patch('{id}', [TagController::class, 'update'])->middleware(['permission:tags.edit'])->name('tags.update');

            // Delete
            Route::delete('{id}', [TagController::class, 'destroy'])->middleware(['permission:tags.destroy'])->name('tags.destroy');
        });

        Route::prefix('users')->group(function(){
			Route::post('validate-avatar', [ProfileController::class, 'validateAvatar'])->middleware(['permission:users.store'])->name('validateAvatar.store');

            Route::get("/", [UserController::class, 'index'])->middleware(['permission:users.index'])->name('users.index');
            
            // Create
            Route::get('create', [UserController::class, 'create'])->middleware(['permission:users.create'])->name('users.create');
            Route::post('/', [UserController::class, 'store'])->middleware(['permission:users.create'])->name('users.store');

            //Edit
            Route::get('{id}', [UserController::class, 'edit'])->middleware(['permission:users.edit'])->name('users.edit');
            Route::patch('{id}', [UserController::class, 'update'])->middleware(['permission:users.edit'])->name('users.update');

            // Delete
            Route::delete('{id}', [UserController::class, 'destroy'])->middleware(['permission:users.destroy'])->name('users.destroy');
        });

        Route::prefix('permissions')->group(function(){
            Route::get("/", [PermissionController::class, 'index'])->middleware(['permission:permissions.index'])->name('permissions.index');
            
            // Create
            Route::get('create', [PermissionController::class, 'create'])->middleware(['permission:permissions.create'])->name('permissions.create');
            Route::post('/', [PermissionController::class, 'store'])->middleware(['permission:permissions.create'])->name('permissions.store');

            //Edit
            Route::get('{id}', [PermissionController::class, 'edit'])->middleware(['permission:permissions.edit'])->name('permissions.edit');
            Route::patch('{id}', [PermissionController::class, 'update'])->middleware(['permission:permissions.edit'])->name('permissions.update');

            // Delete
            Route::delete('{id}', [PermissionController::class, 'destroy'])->middleware(['permission:permissions.destroy'])->name('permissions.destroy');
        });

        Route::prefix('roles')->group(function(){
            Route::get("/", [RoleController::class, 'index'])->middleware(['permission:roles.index'])->name('roles.index');
            
            // Create
            Route::get('create', [RoleController::class, 'create'])->middleware(['permission:roles.create'])->name('roles.create');
            Route::post('/', [RoleController::class, 'store'])->middleware(['permission:roles.create'])->name('roles.store');

            //Edit
            Route::get('{id}', [RoleController::class, 'edit'])->middleware(['permission:roles.edit'])->name('roles.edit');
            Route::patch('{id}', [RoleController::class, 'update'])->middleware(['permission:roles.edit'])->name('roles.update');

            // Delete
            Route::delete('{id}', [RoleController::class, 'destroy'])->middleware(['permission:roles.destroy'])->name('roles.destroy');
        });

        Route::prefix('orders')->group(function(){
            Route::get("/", [OrderController::class, 'index'])->middleware(['permission:orders.index'])->name('orders.index');

            //Edit
            Route::get('{id}', [OrderController::class, 'edit'])->middleware(['permission:orders.edit'])->name('orders.edit');
            Route::patch('{id}', [OrderController::class, 'update'])->middleware(['permission:orders.edit'])->name('orders.update');

            // Delete
            Route::delete('{id}', [OrderController::class, 'destroy'])->middleware(['permission:orders.destroy'])->name('orders.destroy');
        });

        Route::prefix('products')->group(function(){
            /*/ PRODUCT TYPE /*/
            Route::get('type', [ProductTypeController::class, 'index'])->middleware(['permission:product_types.index'])->name('product_types.index');

            // Create
            Route::get('type/create', [ProductTypeController::class, 'create'])->middleware(['permission:product_types.create'])->name('product_types.create');
            Route::post('type', [ProductTypeController::class, 'store'])->middleware(['permission:product_types.create'])->name('product_types.store');

            // Edit
            Route::get('type/{id}', [ProductTypeController::class, 'edit'])->middleware(['permission:product_types.edit'])->name('product_types.edit');
            Route::patch('type/{id}', [ProductTypeController::class, 'update'])->middleware(['permission:product_types.edit'])->name('product_types.update');

            // Delete
            Route::delete('type/{id}', [ProductTypeController::class, 'destroy'])->middleware(['permission:product_types.destroy'])->name('product_types.destroy');

            /*/ PRODUCT /*/

            Route::get("/", [ProductController::class, 'index'])->middleware(['permission:products.index'])->name('products.index');
            
            // Create
            Route::get('create', [ProductController::class, 'create'])->middleware(['permission:products.create'])->name('products.create');
            Route::post('/', [ProductController::class, 'store'])->middleware(['permission:products.create'])->name('products.store');

            //Edit
            Route::get('{id}', [ProductController::class, 'edit'])->middleware(['permission:products.edit'])->name('products.edit');
            Route::patch('{id}', [ProductController::class, 'update'])->middleware(['permission:products.edit'])->name('products.update');

            // Delete
            Route::delete('{id}', [ProductController::class, 'destroy'])->middleware(['permission:products.destroy'])->name('products.destroy');
        });

        Route::prefix('ranks')->group(function(){
            Route::get("/", [RankController::class, 'index'])->middleware(['permission:ranks.index'])->name('ranks.index');
            
            // Create
            Route::get('create', [RankController::class, 'create'])->middleware(['permission:ranks.create'])->name('ranks.create');
            Route::post('/', [RankController::class, 'store'])->middleware(['permission:ranks.create'])->name('ranks.store');

            //Edit
            Route::get('{id}', [RankController::class, 'edit'])->middleware(['permission:ranks.edit'])->name('ranks.edit');
            Route::patch('{id}', [RankController::class, 'update'])->middleware(['permission:ranks.edit'])->name('ranks.update');

            // Delete
            Route::delete('{id}', [RankController::class, 'destroy'])->middleware(['permission:ranks.destroy'])->name('ranks.destroy');
        });
        
        // :HOME SLIDE
        Route::prefix('slider')->group(function(){
            Route::get("/", [SliderController::class, 'index'])->middleware(['permission:slider.index'])->name('slider.index');
            
            // Create
            Route::get('create', [SliderController::class, 'create'])->middleware(['permission:slider.create'])->name('slider.create');
            Route::post('/', [SliderController::class, 'store'])->middleware(['permission:slider.create'])->name('slider.store');

            //Edit
            Route::get('{id}', [CategoryController::class, 'edit'])->middleware(['permission:slider.edit'])->name('slider.edit');
            Route::patch('{id}', [SliderController::class, 'update'])->middleware(['permission:slider.edit'])->name('slider.update');

            // Delete
            Route::delete('{id}', [SliderController::class, 'destroy'])->middleware(['permission:slider.destroy'])->name('slider.destroy');
        });

        // :SETTINGS
        // Route::prefix('settings')->group(function(){
        //     Route::get("/", [SettingsController::class, 'index'])->middleware(['permission:settings.index'])->name('settings.index');
        //     // :UPDATE
        //     Route::patch('/', [SettingsController::class, 'update'])->middleware(['permission:settings.edit'])->name('settings.update');

        //     // :ADS
        //     Route::get("/ads", [SettingsController::class, 'ads'])->middleware(['permission:settings.ads.index'])->name('settings.ads.index');
        //     Route::post("/ads", [SettingsController::class, 'adsStore'])->middleware(['permission:settings.ads.index'])->name('settings.ads.store');
        //     Route::patch("/ads", [SettingsController::class, 'adsUpdate'])->middleware(['permission:settings.ads.update'])->name('settings.ads.update');

        //     // :SEO
        //     Route::get("/seo", [SettingsController::class, 'seo'])->middleware(['permission:settings.seo.index'])->name('settings.seo.index');
        //     Route::post("/seo", [SettingsController::class, 'seoStore'])->middleware(['permission:settings.seo.index'])->name('settings.seo.store');
        //     Route::patch("/seo", [SettingsController::class, 'seoUpdate'])->middleware(['permission:settings.seo.update'])->name('settings.seo.update');
        // });
    });
});
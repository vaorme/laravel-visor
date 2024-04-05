<?php

use App\Http\Controllers\Dashboard\ChaptersController;
use App\Http\Controllers\Dashboard\ChapterUploadController;
use App\Http\Controllers\Dashboard\ComicCategoriesController;
use App\Http\Controllers\Dashboard\ComicDemographiesController;
use App\Http\Controllers\Dashboard\ComicsController;
use App\Http\Controllers\Dashboard\ComicStatusController;
use App\Http\Controllers\Dashboard\ComicTagsController;
use App\Http\Controllers\Dashboard\ComicTypesController;
use App\Http\Controllers\Dashboard\ConfigurationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\uploadChaptersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:developer|administrador|moderador'])->group(function () {
    Route::prefix('space')->group(function(){
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

        // ?: COMICS
        Route::prefix('comics')->group(function(){
            Route::get('/', [ComicsController::class, 'index'])->name('comics.index');

			// ? TYPES
			Route::prefix('types')->group(function(){
				Route::get('/', [ComicTypesController::class, 'index'])->name('comics.types.index');

				// * CREATE
				Route::get('/add', [ComicTypesController::class, 'create'])->name('comics.types.create');
				Route::post('/add', [ComicTypesController::class, 'store'])->name('comics.types.store');

				// * EDIT
				Route::get('/{id}', [ComicTypesController::class, 'show'])->name('comics.types.show');
				Route::put('/{id}', [ComicTypesController::class, 'update'])->name('comics.types.update');

				// * DELETE
				Route::delete('/{id}', [ComicTypesController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('comics.types.destroy');
			});

			// ? STATUS
			Route::prefix('status')->group(function(){
				Route::get('/', [ComicStatusController::class, 'index'])->name('comics.status.index');

				// * CREATE
				Route::get('/add', [ComicStatusController::class, 'create'])->name('comics.status.create');
				Route::post('/add', [ComicStatusController::class, 'store'])->name('comics.status.store');

				// * EDIT
				Route::get('/{id}', [ComicStatusController::class, 'show'])->name('comics.status.show');
				Route::put('/{id}', [ComicStatusController::class, 'update'])->name('comics.status.update');

				// * DELETE
				Route::delete('/{id}', [ComicStatusController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('comics.status.destroy');
			});

			// ? DEMOGRAPHIES
			Route::prefix('demographies')->group(function(){
				Route::get('/', [ComicDemographiesController::class, 'index'])->name('comics.demographies.index');

				// * CREATE
				Route::get('/add', [ComicDemographiesController::class, 'create'])->name('comics.demographies.create');
				Route::post('/add', [ComicDemographiesController::class, 'store'])->name('comics.demographies.store');

				// * EDIT
				Route::get('/{id}', [ComicDemographiesController::class, 'show'])->name('comics.demographies.show');
				Route::put('/{id}', [ComicDemographiesController::class, 'update'])->name('comics.demographies.update');

				// * DELETE
				Route::delete('/{id}', [ComicDemographiesController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('comics.demographies.destroy');
			});

			// ? CATEGORIES
			Route::prefix('categories')->group(function(){
				Route::get('/', [ComicCategoriesController::class, 'index'])->name('comics.categories.index');

				// * CREATE
				Route::get('/add', [ComicCategoriesController::class, 'create'])->name('comics.categories.create');
				Route::post('/add', [ComicCategoriesController::class, 'store'])->name('comics.categories.store');

				// * EDIT
				Route::get('/{id}', [ComicCategoriesController::class, 'show'])->name('comics.categories.show');
				Route::put('/{id}', [ComicCategoriesController::class, 'update'])->name('comics.categories.update');

				// * DELETE
				Route::delete('/{id}', [ComicCategoriesController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('comics.categories.destroy');
			});

			// ? TAGS
			Route::prefix('tags')->group(function(){
				Route::get('/', [ComicTagsController::class, 'index'])->name('comics.tags.index');

				// * CREATE
				Route::get('/add', [ComicTagsController::class, 'create'])->name('comics.tags.create');
				Route::post('/add', [ComicTagsController::class, 'store'])->name('comics.tags.store');

				// * EDIT
				Route::get('/{id}', [ComicTagsController::class, 'show'])->name('comics.tags.show');
				Route::put('/{id}', [ComicTagsController::class, 'update'])->name('comics.tags.update');

				// * DELETE
				Route::delete('/{id}', [ComicTagsController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('comics.tags.destroy');
			});

            // ? CHAPTERS
            Route::prefix('chapters')->group(function(){

                // * CHAPTERS REORDER
                Route::post('/reorder-chapters', [ChaptersController::class, 'reorder'])->name('reorder-chapters.update');

                //Route::post('/create-chapter/{mangaid}', [ChaptersController::class, 'createChapter'])->middleware(['permission:chapters.create'])->name('create-chapter.store');

                // * UPLOAD SINGLE IMAGE
                Route::post('/upload-single-image', [ChapterUploadController::class, 'uploadSingleImage'])->middleware(['permission:chapters.create'])->name('upload-single-image.store');

                // * CHAPTER UPLOAD (FILE)
                Route::post('upload/{mangaid}', [ChapterUploadController::class, 'store'])->middleware(['permission:chapters.store'])->name('upload-chapter.store');

                // * CHAPTER UPLOAD IMAGES
                // Route::post('images-upload/{mangaid}', [ChapterUploadController::class, 'subirImagenes'])->middleware(['permission:chapters.create'])->name('images-upload.store');

                // * CHAPTER UPDATE ORDER IMAGES
                Route::post('images-reorder/{chapterid}', [ChapterUploadController::class, 'updateImagesOrder'])->middleware(['permission:chapters.store'])->name('images-reorder.update');

                // * CHAPTER IMAGE DELETE
                Route::post('images-delete/{chapterid}', [ChapterUploadController::class, 'deleteImage'])->middleware(['permission:chapters.destroy'])->name('images-delete.destroy');

                // * GET CHAPTER
                Route::get('/{mangaid}', [ChaptersController::class, 'show'])->middleware(['permission:chapters.index'])->name('chapters.show');

                // * CREATE CHAPTER
                Route::post('/{mangaid}', [ChaptersController::class, 'store'])->middleware(['permission:chapters.create'])->name('chapters.store');

                // * UPDATE CHAPTER
                Route::patch('/{chapterid}', [ChaptersController::class, 'update'])->middleware(['permission:chapters.update'])->name('chapters.update');

                // * DELETE CHAPTER
                Route::delete('/{id}', [ChaptersController::class, 'destroy'])->middleware(['permission:chapter.destroy'])->name('chapter.destroy');
            });

			// ? COMICS
			// * CREATE COMIC
            Route::get('/add', [ComicsController::class, 'create'])->name('comics.create');
            Route::post('/add', [ComicsController::class, 'store'])->name('comics.store');

            // * EDIT COMIC
            Route::get('/{id}', [ComicsController::class, 'edit'])->name('comics.edit');
            Route::put('/{id}', [ComicsController::class, 'update'])->name('comics.update');

            // * DELETE COMIC
            Route::delete('{id}', [ComicsController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('comics.destroy');
        });

		// ? USERS
        Route::prefix('users')->group(function(){
			// * INDEX
            Route::get('/', [UserController::class, 'index'])->name('users.index');

			// * CREATE COMIC
            Route::get('/add', [UserController::class, 'create'])->name('users.create');
            Route::post('/add', [UserController::class, 'store'])->name('users.store');

			// * CHANGE PASSWORD
			Route::put('/change-password', [UserController::class, 'changePassword'])->name('users.change-password');

			// * ACTIVATE ACCOUNT
			Route::put('/activate-account', [UserController::class, 'activateAccount'])->name('users.activate-account');

			// * DEACTIVATE ACCOUNT
			Route::put('/deactivate-account', [UserController::class, 'deactivateAccount'])->name('users.deactivate-account');

			// * GET COINS
			Route::get('/get-coins/{id}', [UserController::class, 'getCoins'])->name('users.get-coins');
			// * ASSIGN COINS
			Route::post('/assign-coins/{id}', [UserController::class, 'assignCoins'])->name('users.assign-coins');

			// * GET DAYS
			Route::get('/get-days/{id}', [UserController::class, 'getDays'])->name('users.get-days');
			// * ASSIGN COINS
			Route::post('/assign-days/{id}', [UserController::class, 'assignDays'])->name('users.assign-days');

            // * EDIT COMIC
            Route::get('/{id}', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('users.update');

            // * DELETE COMIC
            Route::delete('{id}', [UserController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('users.destroy');
        });

		// ? ROLES
        Route::prefix('roles')->group(function(){
			// * INDEX
            Route::get('/', [RoleController::class, 'index'])->name('roles.index');

			// * CREATE
			Route::get('/add', [RoleController::class, 'create'])->name('roles.create');
			Route::post('/add', [RoleController::class, 'store'])->name('roles.store');

			// * GET PERMISSIONS
			Route::get('/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions.index');
			// * GET USER PERMISSIONS
			Route::get('/permissions/{id}', [RoleController::class, 'getUserPermissions'])->name('roles.user.permissions.index');

			// * EDIT
			Route::get('/{id}', [RoleController::class, 'show'])->name('roles.show');
			Route::put('/{id}', [RoleController::class, 'update'])->name('roles.update');

			// * DELETE
			Route::delete('/{id}', [RoleController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('roles.destroy');
        });

		// ? PERMISSIONS
		Route::prefix('permissions')->group(function(){
			Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');

			// * CREATE
			Route::get('/add', [PermissionController::class, 'create'])->name('permissions.create');
			Route::post('/add', [PermissionController::class, 'store'])->name('permissions.store');

			// * EDIT
			Route::get('/{id}', [PermissionController::class, 'show'])->name('permissions.show');
			Route::put('/{id}', [PermissionController::class, 'update'])->name('permissions.update');

			// * DELETE
			Route::delete('/{id}', [PermissionController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('permissions.destroy');
		});
		// ? CONFIGURATION
		Route::prefix('settings')->group(function(){
            Route::get("/", [ConfigurationController::class, 'index'])->middleware(['permission:settings.index'])->name('settings.index');
            // :UPDATE
            Route::patch('/', [ConfigurationController::class, 'update'])->middleware(['permission:settings.edit'])->name('settings.update');

            // :ADS
            Route::get("/ads", [ConfigurationController::class, 'ads'])->middleware(['permission:settings.ads.index'])->name('settings.ads.index');
            Route::post("/ads", [ConfigurationController::class, 'adsStore'])->middleware(['permission:settings.ads.index'])->name('settings.ads.store');
            Route::patch("/ads", [ConfigurationController::class, 'adsUpdate'])->middleware(['permission:settings.ads.update'])->name('settings.ads.update');

            // :SEO
            Route::get("/seo", [ConfigurationController::class, 'seo'])->middleware(['permission:settings.seo.index'])->name('settings.seo.index');
            Route::post("/seo", [ConfigurationController::class, 'seoStore'])->middleware(['permission:settings.seo.index'])->name('settings.seo.store');
            Route::patch("/seo", [ConfigurationController::class, 'seoUpdate'])->middleware(['permission:settings.seo.update'])->name('settings.seo.update');
        });
    });


});

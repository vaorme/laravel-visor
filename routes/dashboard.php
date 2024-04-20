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
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProductTypeController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:developer|administrador|moderador'])->group(function () {
    Route::prefix('space')->group(function(){
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

        // ?: COMICS
        Route::prefix('comics')->group(function(){
            Route::get('/', [ComicsController::class, 'index'])->middleware(['permission:comics.index'])->name('comics.index');

			// ? TYPES
			Route::prefix('types')->group(function(){
				Route::get('/', [ComicTypesController::class, 'index'])->middleware(['permission:comics.types.index'])->name('comics.types.index');

				// * CREATE
				Route::get('/add', [ComicTypesController::class, 'create'])->middleware(['permission:comics.types.create'])->name('comics.types.create');
				Route::post('/add', [ComicTypesController::class, 'store'])->middleware(['permission:comics.types.store'])->name('comics.types.store');

				// * EDIT
				Route::get('/{id}', [ComicTypesController::class, 'show'])->middleware(['permission:comics.types.edit'])->name('comics.types.show');
				Route::put('/{id}', [ComicTypesController::class, 'update'])->middleware(['permission:comics.types.update'])->name('comics.types.update');

				// * DELETE
				Route::delete('/{id}', [ComicTypesController::class, 'destroy'])->middleware(['permission:comics.types.destroy'])->name('comics.types.destroy');
			});

			// ? STATUS
			Route::prefix('status')->group(function(){
				Route::get('/', [ComicStatusController::class, 'index'])->middleware(['permission:comics.status.index'])->name('comics.status.index');

				// * CREATE
				Route::get('/add', [ComicStatusController::class, 'create'])->middleware(['permission:comics.status.create'])->name('comics.status.create');
				Route::post('/add', [ComicStatusController::class, 'store'])->middleware(['permission:comics.status.store'])->name('comics.status.store');

				// * EDIT
				Route::get('/{id}', [ComicStatusController::class, 'show'])->middleware(['permission:comics.status.edit'])->name('comics.status.show');
				Route::put('/{id}', [ComicStatusController::class, 'update'])->middleware(['permission:comics.status.update'])->name('comics.status.update');

				// * DELETE
				Route::delete('/{id}', [ComicStatusController::class, 'destroy'])->middleware(['permission:comics.status.destroy'])->name('comics.status.destroy');
			});

			// ? DEMOGRAPHIES
			Route::prefix('demographies')->group(function(){
				Route::get('/', [ComicDemographiesController::class, 'index'])->middleware(['permission:comics.demography.destroy'])->name('comics.demographies.index');

				// * CREATE
				Route::get('/add', [ComicDemographiesController::class, 'create'])->middleware(['permission:comics.demography.create'])->name('comics.demographies.create');
				Route::post('/add', [ComicDemographiesController::class, 'store'])->middleware(['permission:comics.demography.store'])->name('comics.demographies.store');

				// * EDIT
				Route::get('/{id}', [ComicDemographiesController::class, 'show'])->middleware(['permission:comics.demography.edit'])->name('comics.demographies.show');
				Route::put('/{id}', [ComicDemographiesController::class, 'update'])->middleware(['permission:comics.demography.update'])->name('comics.demographies.update');

				// * DELETE
				Route::delete('/{id}', [ComicDemographiesController::class, 'destroy'])->middleware(['permission:comics.demography.destroy'])->name('comics.demographies.destroy');
			});

			// ? CATEGORIES
			Route::prefix('categories')->group(function(){
				Route::get('/', [ComicCategoriesController::class, 'index'])->middleware(['permission:comics.categories.index'])->name('comics.categories.index');

				// * CREATE
				Route::get('/add', [ComicCategoriesController::class, 'create'])->middleware(['permission:comics.categories.create'])->name('comics.categories.create');
				Route::post('/add', [ComicCategoriesController::class, 'store'])->middleware(['permission:comics.categories.store'])->name('comics.categories.store');

				// * EDIT
				Route::get('/{id}', [ComicCategoriesController::class, 'show'])->middleware(['permission:comics.categories.edit'])->name('comics.categories.show');
				Route::put('/{id}', [ComicCategoriesController::class, 'update'])->middleware(['permission:comics.categories.update'])->name('comics.categories.update');

				// * DELETE
				Route::delete('/{id}', [ComicCategoriesController::class, 'destroy'])->middleware(['permission:comics.categories.destroy'])->name('comics.categories.destroy');
			});

			// ? TAGS
			Route::prefix('tags')->group(function(){
				Route::get('/', [ComicTagsController::class, 'index'])->middleware(['permission:comics.tags.index'])->name('comics.tags.index');

				// * CREATE
				Route::get('/add', [ComicTagsController::class, 'create'])->middleware(['permission:comics.tags.create'])->name('comics.tags.create');
				Route::post('/add', [ComicTagsController::class, 'store'])->middleware(['permission:comics.tags.store'])->name('comics.tags.store');

				// * EDIT
				Route::get('/{id}', [ComicTagsController::class, 'show'])->middleware(['permission:comics.tags.edit'])->name('comics.tags.show');
				Route::put('/{id}', [ComicTagsController::class, 'update'])->middleware(['permission:comics.tags.update'])->name('comics.tags.update');

				// * DELETE
				Route::delete('/{id}', [ComicTagsController::class, 'destroy'])->middleware(['permission:comics.tags.destroy'])->name('comics.tags.destroy');
			});

            // ? CHAPTERS
            Route::prefix('chapters')->group(function(){

                // * CHAPTERS REORDER
                Route::post('/reorder-chapters', [ChaptersController::class, 'reorder'])->middleware(['permission:chapters.reorder.update'])->name('chapters.reorder.update');

                //Route::post('/create-chapter/{mangaid}', [ChaptersController::class, 'createChapter'])->middleware(['permission:chapters.create'])->name('create-chapter.store');

                // * UPLOAD SINGLE IMAGE
                Route::post('/upload-single-image', [ChapterUploadController::class, 'uploadSingleImage'])->middleware(['permission:chapters.upload_single_image.store'])->name('chapters.upload_single_image.store');

                // * CHAPTER UPLOAD (FILE)
                Route::post('upload/{mangaid}', [ChapterUploadController::class, 'store'])->middleware(['permission:chapters.upload.store'])->name('chapters.upload.store');

                // * CHAPTER UPLOAD IMAGES
                // Route::post('images-upload/{mangaid}', [ChapterUploadController::class, 'subirImagenes'])->middleware(['permission:chapters.create'])->name('images-upload.store');

                // * CHAPTER UPDATE ORDER IMAGES
                Route::post('images-reorder/{chapterid}', [ChapterUploadController::class, 'updateImagesOrder'])->middleware(['permission:chapters.images_reorder.update'])->name('chapters.images_reorder.update');

                // * CHAPTER IMAGE DELETE
                Route::post('images-delete/{chapterid}', [ChapterUploadController::class, 'deleteImage'])->middleware(['permission:chapters.images_delete.destroy'])->name('chapters.images_delete.destroy');

                // * GET CHAPTER
                Route::get('/{mangaid}', [ChaptersController::class, 'show'])->middleware(['permission:chapters.edit'])->name('chapters.show');

                // * CREATE CHAPTER
                Route::post('/{mangaid}', [ChaptersController::class, 'store'])->middleware(['permission:chapters.store'])->name('chapters.store');

                // * UPDATE CHAPTER
                Route::patch('/{chapterid}', [ChaptersController::class, 'update'])->middleware(['permission:chapters.update'])->name('chapters.update');

                // * DELETE CHAPTER
                Route::delete('/{id}', [ChaptersController::class, 'destroy'])->middleware(['permission:chapters.destroy'])->name('chapter.destroy');
            });

			// ? COMICS
			// * CREATE COMIC
            Route::get('/add', [ComicsController::class, 'create'])->middleware(['permission:comics.create'])->name('comics.create');
            Route::post('/add', [ComicsController::class, 'store'])->middleware(['permission:comics.store'])->name('comics.store');

            // * EDIT COMIC
            Route::get('/{id}', [ComicsController::class, 'edit'])->middleware(['permission:comics.edit'])->name('comics.edit');
            Route::put('/{id}', [ComicsController::class, 'update'])->middleware(['permission:comics.update'])->name('comics.update');

            // * DELETE COMIC
            Route::delete('{id}', [ComicsController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('comics.destroy');
        });

		// ? PRODUCTS

		Route::prefix('products')->group(function(){
            
			// * TYPES
			Route::prefix('types')->group(function(){
				Route::get('/', [ProductTypeController::class, 'index'])->middleware(['permission:products.types.index'])->name('products.types.index');

				// * CREATE
				Route::get('/add', [ProductTypeController::class, 'create'])->middleware(['permission:products.types.create'])->name('products.types.create');
				Route::post('/add', [ProductTypeController::class, 'store'])->middleware(['permission:products.types.store'])->name('products.types.store');

				// * EDIT
				Route::get('/{id}', [ProductTypeController::class, 'show'])->middleware(['permission:products.types.edit'])->name('products.types.show');
				Route::put('/{id}', [ProductTypeController::class, 'update'])->middleware(['permission:products.types.update'])->name('products.types.update');

				// * DELETE
				Route::delete('/{id}', [ProductTypeController::class, 'destroy'])->middleware(['permission:products.types.destroy'])->name('products.types.destroy');
			});

            // * PRODUCT
            Route::get("/", [ProductController::class, 'index'])->middleware(['permission:products.index'])->name('products.index');
            
            // * CREATE
            Route::get('create', [ProductController::class, 'create'])->middleware(['permission:products.create'])->name('products.create');
            Route::post('/', [ProductController::class, 'store'])->middleware(['permission:products.create'])->name('products.store');

            // * EDIT
            Route::get('{id}', [ProductController::class, 'edit'])->middleware(['permission:products.edit'])->name('products.edit');
            Route::put('{id}', [ProductController::class, 'update'])->middleware(['permission:products.update'])->name('products.update');

            // * DELETE
            Route::delete('{id}', [ProductController::class, 'destroy'])->middleware(['permission:products.destroy'])->name('products.destroy');
        });

		// ? ORDERS

		Route::prefix('orders')->group(function(){

            // * PRODUCT
            Route::get("/", [OrderController::class, 'index'])->middleware(['permission:orders.index'])->name('orders.index');
            
            // * CREATE
            Route::get('create', [OrderController::class, 'create'])->middleware(['permission:orders.create'])->name('orders.create');
            Route::post('/', [OrderController::class, 'store'])->middleware(['permission:orders.store'])->name('orders.store');

            // * EDIT
            Route::get('{id}', [OrderController::class, 'show'])->middleware(['permission:orders.edit'])->name('orders.edit');
            Route::put('{id}', [OrderController::class, 'update'])->middleware(['permission:orders.update'])->name('orders.update');

            // * DELETE
            Route::delete('{id}', [OrderController::class, 'destroy'])->middleware(['permission:orders.destroy'])->name('orders.destroy');
        });

		// ? USERS
        Route::prefix('users')->group(function(){
			// * INDEX
            Route::get('/', [UserController::class, 'index'])->middleware(['permission:users.index'])->name('users.index');

			// * CREATE
            Route::get('/add', [UserController::class, 'create'])->middleware(['permission:users.create'])->name('users.create');
            Route::post('/add', [UserController::class, 'store'])->middleware(['permission:users.store'])->name('users.store');

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

            // * EDIT
            Route::get('/{id}', [UserController::class, 'edit'])->middleware(['permission:users.edit'])->name('users.edit');
            Route::put('/{id}', [UserController::class, 'update'])->middleware(['permission:users.update'])->name('users.update');

            // * DELETE
            Route::delete('{id}', [UserController::class, 'destroy'])->middleware(['permission:users.destroy'])->name('users.destroy');
        });

		// ? ROLES
        Route::prefix('roles')->group(function(){
			// * INDEX
            Route::get('/', [RoleController::class, 'index'])->middleware(['permission:roles.index'])->name('roles.index');

			// * CREATE
			Route::get('/add', [RoleController::class, 'create'])->middleware(['permission:roles.create'])->name('roles.create');
			Route::post('/add', [RoleController::class, 'store'])->middleware(['permission:roles.store'])->name('roles.store');

			// * GET PERMISSIONS
			Route::get('/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions.index');
			// * GET USER PERMISSIONS
			Route::get('/permissions/{id}', [RoleController::class, 'getUserPermissions'])->name('roles.user.permissions.index');

			// * EDIT
			Route::get('/{id}', [RoleController::class, 'show'])->middleware(['permission:roles.edit'])->name('roles.show');
			Route::put('/{id}', [RoleController::class, 'update'])->middleware(['permission:roles.update'])->name('roles.update');

			// * DELETE
			Route::delete('/{id}', [RoleController::class, 'destroy'])->middleware(['permission:roles.destroy'])->name('roles.destroy');
        });

		// ? PERMISSIONS
		Route::prefix('permissions')->group(function(){
			Route::get('/', [PermissionController::class, 'index'])->middleware(['permission:permissions.index'])->name('permissions.index');

			// * CREATE
			Route::get('/add', [PermissionController::class, 'create'])->middleware(['permission:permissions.create'])->name('permissions.create');
			Route::post('/add', [PermissionController::class, 'store'])->middleware(['permission:permissions.store'])->name('permissions.store');

			// * EDIT
			Route::get('/{id}', [PermissionController::class, 'show'])->middleware(['permission:permissions.edit'])->name('permissions.show');
			Route::put('/{id}', [PermissionController::class, 'update'])->middleware(['permission:permissions.update'])->name('permissions.update');

			// * DELETE
			Route::delete('/{id}', [PermissionController::class, 'destroy'])->middleware(['permission:permissions.destroy'])->name('permissions.destroy');
		});
		// ? CONFIGURATION
		Route::prefix('settings')->group(function(){
            Route::get("/", [ConfigurationController::class, 'index'])->middleware(['permission:settings.index'])->name('settings.index');
            // :UPDATE
            Route::put('/', [ConfigurationController::class, 'update'])->middleware(['permission:settings.edit'])->name('settings.update');

            // :ADS
            Route::get("/ads", [ConfigurationController::class, 'ads'])->middleware(['permission:settings.ads.index'])->name('settings.ads.index');
            Route::put("/ads", [ConfigurationController::class, 'adsSave'])->middleware(['permission:settings.ads.update'])->name('settings.ads.update');

            // :SEO
            Route::get("/seo", [ConfigurationController::class, 'seo'])->middleware(['permission:settings.seo.index'])->name('settings.seo.index');
            Route::put("/seo", [ConfigurationController::class, 'seoSave'])->middleware(['permission:settings.seo.update'])->name('settings.seo.update');
        });
    });


});

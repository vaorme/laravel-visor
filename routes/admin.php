<?php

use App\Http\Controllers\ChaptersController;
use App\Http\Controllers\MangaBookStatusController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\MangaDemographyController;
use App\Http\Controllers\MangaTypeController;
use App\Http\Controllers\uploadChaptersController;
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
            Route::delete('{id}', [MangaController::class, 'destroy'])->middleware(['permission:maga.destroy']);
        });

        /* MANGA CHAPTERS */
        Route::post('upload-chapter/{mangaid}', [uploadChaptersController::class, 'store'])->middleware(['permission:chapters.store'])->name('uploadChapter.store');

        Route::post('chapter/{mangaid}', [ChaptersController::class, 'store'])->middleware(['permission:chapters.create'])->name('chapters.store');
    });
});
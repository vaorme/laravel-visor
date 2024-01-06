<?php

use App\Http\Controllers\Dashboard\ChaptersController;
use App\Http\Controllers\Dashboard\ChapterUploadController;
use App\Http\Controllers\Dashboard\ComicsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\uploadChaptersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:developer|administrador|moderador'])->group(function () {
    Route::prefix('space')->group(function(){
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

        // :COMICS
        Route::prefix('comics')->group(function(){
            Route::get('/', [ComicsController::class, 'index'])->name('comics.index');
            
            // * CREATE
            Route::get('/add', [ComicsController::class, 'create'])->name('comics.create');
            Route::post('/add', [ComicsController::class, 'store'])->name('comics.store');

            // * EDIT
            Route::get('/{id}', [ComicsController::class, 'edit'])->name('comics.edit');
            Route::put('/{id}', [ComicsController::class, 'update'])->name('comics.update');

            // * DELETE
            Route::delete('{id}', [ComicsController::class, 'destroy'])->middleware(['permission:manga.destroy'])->name('comics.destroy');

            // ? CHAPTERS
            Route::prefix('chapters')->group(function(){

                // * CHAPTERS REORDER
                Route::post('/reorder-chapters', [ChaptersController::class, 'reorder'])->name('reorder-chapters.update');

                //Route::post('/create-chapter/{mangaid}', [ChaptersController::class, 'createChapter'])->middleware(['permission:chapters.create'])->name('create-chapter.store');

                // * UPLOAD SINGLE IMAGE
                Route::post('/upload-single-image', [ChapterUploadController::class, 'uploadSingleImage'])->middleware(['permission:chapters.create'])->name('upload-single-image.store');

                // * CHAPTER UPLOAD (FILE)
                Route::post('upload/{mangaid}', [ChapterUploadController::class, 'store'])->middleware(['permission:chapters.store'])->name('upload-chapter.store');
                
                // // * CHAPTER UPLOAD IMAGES
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
        });
    });
});
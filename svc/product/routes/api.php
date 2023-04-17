<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProductController;

Route::group(['middleware' => ['json.response']], function () {

    // Brand routes
    Route::put(
        '/brand/create',
        [
            BrandController::class, 'putCreate'
        ]
    );

    Route::get(
        '/brand',
        [
            BrandController::class, 'getByID'
        ]
    );

    Route::get(
        '/brand/analogue-list',
        [
            BrandController::class, 'getBrandAnalogueList'
        ]
    );

    Route::get(
        '/brand/list',
        [
            BrandController::class, 'getList'
        ]
    );

    Route::get(
        '/brand/set-mapping',
        [
            BrandController::class, 'postSetMapping'
        ]
    );

    Route::delete(
        '/brand/remove',
        [
            BrandController::class, 'deleteRemove'
        ]
    );

    // Country routes
    Route::put(
        '/country/create',
        [
            CountryController::class, 'putCreate'
        ]
    );

    Route::get(
        '/country',
        [
            CountryController::class, 'getByID'
        ]
    );

    Route::get(
        '/country/list',
        [
            CountryController::class, 'getList'
        ]
    );

    Route::delete(
        '/country/remove',
        [
            CountryController::class, 'deleteRemove'
        ]
    );

    // Product routes
    Route::put(
        '/product/create',
        [
            ProductController::class, 'putCreate'
        ]
    );

    Route::post(
        '/product/import',
        [
            ProductController::class, 'postImport'
        ]
    );

    Route::post(
        '/product/import-file',
        [
            ProductController::class, 'postImportFile'
        ]
    );

    Route::get(
        '/product',
        [
            ProductController::class, 'getByID'
        ]
    );

    Route::get(
        '/product/list',
        [
            ProductController::class, 'getList'
        ]
    );

    Route::delete(
        '/product/remove',
        [
            ProductController::class, 'deleteRemove'
        ]
    );
});

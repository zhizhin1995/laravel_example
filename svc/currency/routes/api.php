<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyRateController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\BrandController;

Route::group(['middleware' => ['json.response']], function () {
    Route::get(
        '/currency-rate/current',
        [
            CurrencyRateController::class, 'getCurrentRate'
        ]
    );

    Route::get(
        '/currency-rate/by-date',
        [
            CurrencyRateController::class, 'getRateByDate'
        ]
    );

    Route::put(
        '/currency-rate/set-rate',
        [
            CurrencyRateController::class, 'putSetRate'
        ]
    );

    Route::put(
        '/currency/create',
        [
            CurrencyController::class, 'putCreate'
        ]
    );

    Route::delete(
        '/currency/remove',
        [
            CurrencyController::class, 'deleteRemove'
        ]
    );

    Route::post(
        '/currency/convert',
        [
            CurrencyController::class, 'postConvert'
        ]
    );

    Route::get(
        '/currency/list',
        [
            CurrencyController::class, 'getList'
        ]
    );
});

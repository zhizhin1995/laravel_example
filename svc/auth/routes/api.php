<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group(['middleware' => ['json.response']], function () {
    Route::post(
        '/auth/authorize',
        [
            AuthController::class, 'postAuthorize'
        ]
    );

    Route::post(
        '/auth/logout',
        [
            AuthController::class, 'postLogout'
        ]
    );

    Route::post(
        '/auth/register',
        [
            AuthController::class, 'postRegister'
        ]
    );
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserAPIController;
use App\Http\Controllers\API\AuthAPIController;
use App\Http\Controllers\API\AdminAPIPdfController;




Route::post('/user/login', [AuthAPIController::class, 'userLogin']);

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthAPIController::class, 'logout']);

    // User routes
    Route::get('/all/pdfs', [UserAPIController::class, 'index']);

    Route::get('/pdfs/today/summary/{page?}', [UserAPIController::class, 'summary']); // summary

    Route::get('/pdfs/summary/download', [UserAPIController::class, 'downloadSummary']); // summary download

    Route::get('/single/pdfs/show/{id}', [UserAPIController::class, 'show']); // single pdf shows

    Route::get('/pdf-days', [UserAPIController::class, 'getDaysByMonth']);

    Route::get('/pdf/single/days/{date}', [UserAPIController::class, 'pdfsByDay']);

    Route::get('/pdf/download/{id}', [UserAPIController::class, 'downloadPdf']);

    Route::get('/pdf/print/{id}', [UserAPIController::class, 'printPdf']);

    Route::get('/user/info', [UserAPIController::class, 'userInfo']);
});


// Route::post('/login', [AuthAPIController::class, 'login']);
// Route::post('/logout', [AuthAPIController::class, 'logout']); ldap
// Route::get('/session', [AuthAPIController::class, 'me']);




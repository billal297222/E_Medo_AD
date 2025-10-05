<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserAPIController;
use App\Http\Controllers\API\AuthAPIController;
use App\Http\Controllers\API\AdminAPIPdfController;




// Route::post('/user/login', [AuthAPIController::class, 'login']);
// Route::get('/validate', [AuthAPIController::class, 'validateToken']);
// Route::get('/sso-login', [AuthAPIController::class, 'ssoLogin']);



// Traditional login
// Route::post('/user/login', [AuthAPIController::class, 'login']);

// // Validate JWT token
// Route::get('/validate', [AuthAPIController::class, 'validateToken']);

// // SSO login endpoint
// Route::get('/sso-login', [AuthAPIController::class, 'ssoLogin']);


// Redirect user to ADFS login
Route::get('/sso-login', [AuthAPIController::class, 'ssoLogin']);

// ACS endpoint (handle ADFS response)
Route::post('/saml2/acs', [AuthAPIController::class, 'samlAcs']);

// SLS endpoint (handle logout)
Route::get('/saml2/sls', [AuthAPIController::class, 'samlSls']);





Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthAPIController::class, 'logout']);

    // User routes
    Route::get('/all/pdfs', [UserAPIController::class, 'index']);

    Route::get('/pdfs/today/summary/{page?}/{per_page?}', [UserAPIController::class, 'summary']); // summary

    Route::get('/pdfs/summary/download/{page?}/{per_page?}', [UserAPIController::class, 'downloadSummary']); // summary download

    Route::get('/single/pdfs/show/{id}', [UserAPIController::class, 'show']); // single pdf shows

    Route::get('/pdf-days', [UserAPIController::class, 'getDaysByMonth']);

    Route::get('/pdf/single/days/{date}', [UserAPIController::class, 'pdfsByDay']);

    Route::get('/pdf/download/{id}', [UserAPIController::class, 'downloadPdf']);

    Route::get('/pdf/print/{id}', [UserAPIController::class, 'printPdf']);

    Route::get('/user/info', [UserAPIController::class, 'userInfo']);
});





<?php

use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('clear-cache', function () {
    Artisan::call('cache:clear');
});

Route::group(['prefix' => 'certificates'], function () {
    Route::get('/', [CertificateController::class, 'index']);
    Route::get('/all', [CertificateController::class, 'allCertificates']);
    Route::get('validation', [CertificateController::class, 'validationView']);
    Route::get('validation/{id}', [CertificateController::class, 'validation']);
    Route::post('generate', [CertificateController::class, 'store']);
    Route::post('resend/{id}', [CertificateController::class, 'resend']);
    Route::post('approve/{id}', [CertificateController::class, 'approve']);
    Route::delete('delete/{certificate}', [CertificateController::class, 'destroy']);
});

<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Artisan;

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
    Route::get('/', 'CertificateController@index');
    Route::get('/all', 'CertificateController@allCertificates');
    Route::get('validation', 'CertificateController@validationView');
    Route::post('generate', 'CertificateController@store');
    Route::post('validation/{id}', 'CertificateController@validation');
    Route::post('resend/{id}', 'CertificateController@resend');
    Route::post('approve/{id}', 'CertificateController@approve');
    Route::delete('delete/{certificate}', 'CertificateController@destroy');
});

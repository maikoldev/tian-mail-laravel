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

Route::get('clear-cache', function() {
  Artisan::call('cache:clear');
});

Route::post('generate-certificate', 'CertificateController@store');
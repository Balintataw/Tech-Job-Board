<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/{any}', 'SpaController@index')->where('any', '.*');

// Route::get('/', 'JobController@index');
// Route::get('/jobs/{id}/{job}', 'JobController@show')->name('jobs.show');

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {

	Route::group(['middleware' => 'auth:api'], function() {
		// protected user routes
		Route::group(['prefix' => 'user'], function() {
			Route::get('/profile', 'UserprofileController@index');
			Route::put('/profile', 'UserprofileController@update');
			Route::post('/profile/resume', 'UserprofileController@resume');
			Route::post('/profile/coverletter', 'UserprofileController@coverletter');
			Route::post('/profile/avatar', 'UserprofileController@avatar');
		});

	});


	Route::group(['prefix' => 'categories'], function () {
		Route::get('/', 'CategoryController@index');
	});

	Route::group(['prefix' => 'jobs'], function () {
		Route::get('/', 'JobController@index');
		Route::get('/{id}/{job}', 'JobController@show');
		Route::post('/create', 'JobController@create');
	});

	Route::group(['prefix' => 'company'], function () {
		Route::get('/profile', 'CompanyController@profile');
		Route::get('/{id}/{company}', 'CompanyController@index');
		Route::put('/profile', 'CompanyController@update')->middleware('auth:api');
		Route::post('/profile/coverphoto', 'CompanyController@coverphoto')->middleware('auth:api');
		Route::post('/profile/logo', 'CompanyController@logo')->middleware('auth:api');
	});

	Route::post('download', 'UserprofileController@download');
});

Route::group([
	// 'middleware' => 'auth:api',
	'prefix' => 'v1/auth'
], function ($router) {

	Route::post('/login', 'AuthController@login');
	Route::post('/register', 'AuthController@register');
	Route::post('/register/employer', 'AuthController@registerEmployer');

	Route::middleware('auth:api')->get('/me', function(Request $request) {
		return $request->user();
	})->name('me');
	Route::middleware('auth:api')->get('/logout', 'AuthController@logout');

});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

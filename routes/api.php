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

	// Route::group(['middleware' => 'auth:api'], function() {

	// });


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
		Route::put('/profile', 'CompanyController@update')->middleware('auth');
		Route::post('/profile/coverphoto', 'CompanyController@coverphoto')->middleware('auth');
		Route::post('/profile/logo', 'CompanyController@logo')->middleware('auth');
	});

	Route::post('download', 'UserprofileController@download');
	Route::get('user/profile', 'UserprofileController@index')->middleware('auth');
	Route::put('user/profile', 'UserprofileController@update')->middleware('auth');
	Route::post('user/profile/avatar', 'UserprofileController@avatar')->middleware('auth');
	Route::post('user/profile/resume', 'UserprofileController@resume')->middleware('auth');
	Route::post('user/profile/coverletter', 'UserprofileController@coverletter')->middleware('auth');
});

Route::group([
	// 'middleware' => 'auth:api',
	'prefix' => 'v1/auth'
], function ($router) {

	Route::post('/login', 'AuthController@login');
	Route::post('/logout', 'AuthController@logout');
	Route::post('/register', 'AuthController@register');
	Route::post('/register/employer', 'AuthController@registerEmployer');
	Route::get('/me', function(Request $request) {
		return $request->user();
	})->middleware('auth:api')->name('me');

});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

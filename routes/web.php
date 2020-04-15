<?php

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


Route::group(['middleware' => 'guest:owner', ['except' =>['logout']]], function () {
	Route::get('/front/login', 'FrontController@showOwnerLoginForm');
	Route::post('/front/login', 'FrontController@ownerLogin');
});

Route::group(['middleware' => 'guest:owner'], function () {
	Route::get('/front/register', 'FrontController@showOwnerRegisterForm');
	Route::post('/front/register', 'FrontController@createOwner');
});

Route::post('/front/logout', 'FrontController@logoutUser');

Route::group(['middleware' => 'auth:owner'], function () {
	Route::get('/front/home', 'FrontController@frontHome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
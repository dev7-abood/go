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

Route::resource('/api/categories', 'CategoryController')->middleware('auth');
Route::resource('/api/pages', 'PageController')->middleware('auth');
Route::resource('/api/products', 'ProductController')->middleware('auth');
Route::resource('/api/users', 'UserController')->middleware('auth');
Route::get('/api/listproduct', 'HelperController@product_json')->middleware('auth');
Route::post('/api/listproduct', 'HelperController@product_json')->middleware('auth');
Route::post('/api/changepassword', 'HelperController@passwordchange')->middleware('auth');
Route::post('/api/changename', 'HelperController@fullnamechange')->middleware('auth');
Route::get('/data/json/{user_id}/{url}', 'HelperController@data_json');
Route::get('/api/links', 'HelperController@Generate_Links');
Route::get('/api/user', function (Request $request) {
    return auth()->user();
});

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/showfile/{filename}', function ($filename)
{
    $path = storage_path('app') . '/'  . $filename;
    return response()->file($path);;
})->where('filename', '.*');


Route::get('/{any}', 'HomeController@index')->where('any', '.*');


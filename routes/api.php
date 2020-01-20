<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources(["user" => "API\UserController"]);
Route::apiResources(["account" => "API\AccountController"]);
Route::apiResources(["category" => "API\CategoryController"]);
Route::apiResources(["transaction" => "API\TransactionController"]);

Route::get("profile", "API\UserController@profile");
Route::get("findUser", "API\UserController@search");
Route::get("getCategories", "API\CategoryController@getCategories");
Route::put("profile", "API\UserController@updateProfile");

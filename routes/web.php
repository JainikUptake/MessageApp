<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

// Route::get('dashboard', function () {
//     return view('welcome');
// });

Route::get('dashboard', 'App\Http\Controllers\AuthController@dashboard');
Route::match (['get', 'post'], 'login', 'App\Http\Controllers\AuthController@login');
Route::get('logout', 'App\Http\Controllers\AuthController@logout')->name('logout');
Route::get('/get-chat-history', 'App\Http\Controllers\ChatController@getChatHistory');
Route::post('/save-chat-message', 'App\Http\Controllers\ChatController@saveChatMessage');

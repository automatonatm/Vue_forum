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



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



Route::get('/threads', 'ThreadsController@index')->name('threads');


Route::get('/threads/create', 'ThreadsController@create')->name('threads.create');

Route::post('/threads', 'ThreadsController@store')->name('threads.store')->middleware('must-be-confirmed');

Route::get('/threads/channel/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');

Route::delete('/threads/channel/{channel}/{thread}', 'ThreadsController@destroy');

Route::get('/threads/{channel}', 'ThreadsController@index')->name('threads.channel');

Route::get('/threads/channel/{channel}/{thread}/replies', 'ReplyController@index')->name('reply.fetch');

Route::post('/threads/channel/{channel}/{thread}/replies', 'ReplyController@store')->name('reply.store');

Route::post('/threads/channel/{channel}/{thread}/subscribe', 'ThreadSubscriptionsController@store')->name('thread.subscribe');

Route::delete('/threads/channel/{channel}/{thread}/subscribe', 'ThreadSubscriptionsController@destroy')->name('subscribe.remove');


Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('reply.destroy');

Route::patch('/replies/{reply}', 'ReplyController@update')->name('reply.patch');

Route::post('/replies/{reply}/favorites', 'FavoriteController@store')->name('reply.like');

Route::delete('/replies/{reply}/favorites/', 'FavoriteController@destroy')->name('reply.destroy');

//Mark Reply as Best

Route::post('/replies/{reply}/best', 'BestReplyController@store')->name('best-reply.store');




Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');


Route::get('/profiles/{user}/Notifications', 'UserNotificationsController@index')->name('noty.fetch');


Route::delete('/profiles/{user}/Notifications/{notification}', 'UserNotificationsController@destroy')->name('noty.destroy');


/*Search*/
Route::get('/api/users', 'Api\UsersController@index');

Route::post('/api/users/{user}/avatar', 'Api\AvatarController@store')->name('avatar');


//Registeration confrimation

Route::get('register/confirm', 'Auth\RegistrationConfirmation@index')->name('confirm');




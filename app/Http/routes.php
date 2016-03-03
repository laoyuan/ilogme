<?php


/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => ['web']], function () {
    Route::auth();
    Route::resource('span', 'SpanController');
    Route::resource('type', 'TypeController');
    Route::resource('todo', 'TodoController', ['only' => ['store', 'update', 'destroy']]);
    Route::resource('note', 'NoteController');
    Route::resource('pic', 'PicController');

    Route::get('/test', function () {
        return view('test');
    });

    Route::get('/', 'UserController@index');
    Route::get('/{name}/{date?}', 'UserController@home')->where('date','[\d]{8}');
    Route::post('/span/{id}/end', 'SpanController@end')->where('id','\d+');

    Route::get('/u/settings', 'Auth\AuthController@edit');
    Route::post('/u/settings/email', 'Auth\AuthController@updateEmail');
    Route::post('/u/settings/password', 'Auth\AuthController@updatePassword');
});

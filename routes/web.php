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
| Route::get|post|any|match|group...
*/

/*
 *
 */

//工具类
Route::namespace('Tools')->group(function(){
    Route::get('toolsTimestampConversion', [
        'uses' =>  'ToolsController@timestampConversion'
    ]);
});

Route::get('demo/section1', [
    'uses' => 'DemoController@section1'
]);

Route::get('demo/view1', [
    'uses' => 'DemoController@view1'
]);


Route::get('demo/hello', [
    'uses' => 'DemoController@hello',
    'as' => 'demoHello'
]);

Route::get('demo/{id}', [
    'uses' => 'DemoController@bindParams'
])->where(['id' => '[0-9]+']);

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

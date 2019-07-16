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

Route::apiResource('demoNo2', 'Demo2Controller', [
    'test1' => 'test1'
])->middleware('debug');

//工具类
Route::middleware('debug')->namespace('Tools')->group(function(){
    Route::post('tools{action}Ajax', function($action) {
        $namespace = 'App\Http\Controllers\Tools\\';
        $className  = $namespace.'ToolsController';
        $tempObj = new $className;
        $action .= 'Ajax';
        return call_user_func([$tempObj, $action]);
    });

    Route::get('tools{action}', function($action) {
        $namespace = 'App\Http\Controllers\Tools\\';
        $className  = $namespace.'ToolsController';
        $tempObj = new $className;
        return call_user_func([$tempObj, $action]);
    });

    Route::get('tools', [
        'uses' => 'ToolsController@index'
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


//自动路由
Route::get('/{controller}/{action}', function ($controller, $action) {
    $namespace = 'App\Http\Controllers\\';
    $className = $namespace . ucfirst($controller . "Controller");
    $tempObj = new $className();
    return call_user_func(array($tempObj, $action));
});
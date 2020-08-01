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
Route::get('/info', function () {
    phpinfo();

});

Route::get('/redis/hash1','TextController@hash1');

//接口测试
Route::post('/user/reg','User\IndexController@reg');
//登录
Route::post('/user/login','User\IndexController@login');
//个人中心
Route::get('/user/center','User\IndexController@center')->middleware('verify.token','count');
//商品
Route::get('/goods','User\IndexController@goods');




Route::get('/test1','TestController@test1')->middleware('count');
Route::post('/test/dec','TestController@dec');

Route::get('/test/aes1','TestController@aes1');

Route::get('/test/rsa1','TestController@rsa1');

Route::get('/test/sign1','TestController@sign1');


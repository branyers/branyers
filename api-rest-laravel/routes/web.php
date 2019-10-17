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

//Cargando clases

use App\Http\Middleware\ApiAuthMiddleware;

//RUTA DE PRUEBAS

Route::get('/', function () {
    return view('welcome');
});


Route::get('/pruebaController/{nombre}',function ($nombre){
    $texto = 'Prueba desde las rutas ';
    $texto .= 'Nombre '.$nombre;

    return view('pruebaController',array(
        'texto' => $texto
    ));

});


Route::get('/animales','pruebaController@index');
Route::get('/test-orm','pruebaController@testOrm');


//Rutas de la API

//RUTAS DE PRUEBAS
//    route::get('/usuarios/prueba','UserController@pruebas');
//    route::get('/post/prueba','PostController@pruebas');
//    route::get('/category/prueba','CategoryController@pruebas');

//Rutas oficiales de la API




//Rutas del controlador de Usuario

Route::post('/api/register','UserController@register');
Route::post('/api/login','UserController@login');
Route::put('/api/user/update','UserController@update');
Route::post('/api/user/upload','UserController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/api/user/avatar/{filename}','UserController@getImage');
Route::get('/api/user/detail/{id}','UserController@detail');


//Rutas del controlador de Categorias
Route::resource('/api/category', 'CategoryController');


//Rutas del controlador de Post
Route::resource('/api/post', 'PostController');
Route::post('/api/post/upload','PostController@upload');
Route::get('/api/post/image/{filename}','PostController@getImage');
Route::get('/api/post/category/{id}','PostController@getPostsByCategory');
Route::get('/api/post/user/{id}','PostController@getPostsByUser');






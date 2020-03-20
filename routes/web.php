<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/home', function () {
    return view('home');
});
//Preparar array TxT
Route::post('import/show', 'ImportController@show')->name('import.filetxt');

//Emerson
Route::get('ediemerson/path', 'EdiemersonController@path')->name('ediemerson.path');
Route::get('ediemerson/insertar', 'EdiemersonController@store')->name('ediemerson.insertar');
Route::get('ediemerson/creartxt', 'EdiemersonController@create')->name('ediemerson.creartxt');

//Daimler
Route::get('edidaimler/path', 'EdidaimlerController@path')->name('edidaimler.path');
Route::get('edidaimler/insertar', 'EdidaimlerController@store')->name('edidaimler.insertar');
Route::get('edidaimler/creartxt', 'EdidaimlerController@create')->name('edidaimler.creartxt');
Route::get('edidaimler/{id}', 'EdidaimlerController@show')->name('edidaimler.id');
Route::get('edidaimler/alert/', 'EdidaimlerController@alert')->name('edidaimler.alert');
Route::post('/edidaimler/respuesta', 'EdidaimlerController@respuesta')->name('respuesta');


//test
Route::get('import/index', 'ImportController@index')->name('import');
Route::get('import/path', 'ImportController@path')->name('import.path');
Route::get('import/insertar', 'ImportController@store')->name('import.insertar');
Route::get('import/creartxt', 'ImportController@create')->name('import.creartxt');
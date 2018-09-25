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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

//Folder actions
Route::get('all_folders', 'FolderController@folder_list')->name('folder_list');
Route::post('create_folder', 'FolderController@create_folder')->name('create');
Route::post('delete_folder/{id}', 'FolderController@delete_folder')->name('delete');
Route::post('edit_name/{id}', 'FolderController@edit_name')->name('edit');

//File Actions
Route::get('all_files/{folder_id}', 'FileController@display')->name('files_list');
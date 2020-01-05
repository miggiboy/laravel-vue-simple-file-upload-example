<?php

Route::view('/', '/welcome');

Route::post('/file-uploads', 'FileUploadsController@store')->name('file-uploads.store');
Route::delete('/file-uploads', 'FileUploadsController@destroy')->name('file-uploads.destroy');

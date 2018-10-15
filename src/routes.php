<?php
Route::post('_uploaderTeste', 'marcusvbda\uploader\Controllers\UploaderController@teste')->name("_uploader");
Route::get('_teste', 'marcusvbda\uploader\Controllers\UploaderController@teste2')->name("_uploader");
Route::get('file/{slug}', 'marcusvbda\uploader\Controllers\UploaderController@getFile')->name("_getFile");
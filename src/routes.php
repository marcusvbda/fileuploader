<?php
Route::get('files/get/{slug}.{extension}', 'marcusvbda\uploader\Controllers\UploaderController@getFile')->name("uploader.files.get");
Route::get('files/get/thumbnail/{slug}.{extension}', 'marcusvbda\uploader\Controllers\UploaderController@getThumbnail')->name("uploader.files.get.thumbnail");

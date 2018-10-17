<?php
Route::get('files/get/{slug}', 'marcusvbda\uploader\Controllers\UploaderController@getFile')->name("uploader.files.get");

<?php
Route::get('files/get/{slug}.{extension}', 'marcusvbda\uploader\Controllers\UploaderController@getFile')->name("uploader.files.get");

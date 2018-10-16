<?php
Route::get('file/{slug}', 'marcusvbda\uploader\Controllers\UploaderController@getFile')->name("uploader.file.get");

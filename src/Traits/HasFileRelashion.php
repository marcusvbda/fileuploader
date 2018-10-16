<?php namespace marcusvbda\uploader\Traits;

use marcusvbda\uploader\Models\File as _Files;
use Illuminate\Support\Facades\Storage;
use marcusvbda\uploader\Requests\UploadFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use  marcusvbda\uploader\Models\{FileCategoryRelashion};


trait HasFileRelashion
{
	public function addFile($file_id)
	{
        return FileCategoryRelashion::create([
            '_files_category_id' => $this->id,
            'file_id'            => $file_id
        ]);
    }
    
    public function files()
    {
        return FileCategoryRelashion::where("_files_category_id",$this->id);
    }

}
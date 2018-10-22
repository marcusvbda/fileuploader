<?php namespace marcusvbda\uploader\Traits;

use marcusvbda\uploader\Models\File as _File;
use Illuminate\Support\Facades\Storage;
use marcusvbda\uploader\Requests\UploadFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use  marcusvbda\uploader\Models\{FileCategoryRelation};


trait HasFileRelation
{
	public function addFile(_File $file)
	{
        return FileCategoryRelation::create([
            'file_category_id'   => $this->id,
            'file_id'            => $file->id
        ]);
    }
    
    public function files()
    {
        return FileCategoryRelation::where("file_category_id",$this->id);
    }

}
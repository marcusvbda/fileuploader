<?php namespace marcusvbda\uploader\Traits;

use marcusvbda\uploader\Models\File as _Files;
use Illuminate\Support\Facades\Storage;
use marcusvbda\uploader\Requests\UploadFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use  marcusvbda\uploader\Models\FileRelations;


trait HasFiles
{
	public function addFile($file_id)
	{
        $model = $this->getMorphClass();
        return FileRelations::create([
            'file_model' => $model,
            'ref_id'     => $this->id,
            'file_id'    => $file_id
        ]);
    }
    
    public function files()
    {
        return FileRelations::where("ref_id",$this->id)->get();
    }

    public function removeFile($file_id)
    {
        return FileRelations::where("ref_id",$this->id)->delete();
    }
}
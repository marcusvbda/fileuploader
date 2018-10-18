<?php namespace marcusvbda\uploader\Traits;

use marcusvbda\uploader\Models\File as _File;
use Illuminate\Support\Facades\Storage;
use marcusvbda\uploader\Requests\UploadFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use  marcusvbda\uploader\Models\FileRelation;


trait HasFiles
{
	public function addFile(_File $file)
	{
        $model = $this->getMorphClass();
        $ordination = FileRelation::where("ref_id",$this->id)->max('ordination')+1;
        return FileRelation::create([
            'file_model' => $model,
            'ref_id'     => $this->id,
            'file_id'    => $file->id,
            'ordination' => $ordination
        ]);
    }
    
    public function files()
    {
        return FileRelation::where("ref_id",$this->id)->orderBy("ordination");
    }

    public function removeFile(_File $file)
    {
        return FileRelation::where("ref_id",$this->id)->where("file_id",$file->id)->delete();
    }

}
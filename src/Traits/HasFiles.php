<?php namespace marcusvbda\uploader\Traits;

use marcusvbda\uploader\Models\File as _Files;
use Illuminate\Support\Facades\Storage;
use marcusvbda\uploader\Requests\UploadFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use  marcusvbda\uploader\Models\FileRelation;


trait HasFiles
{
	public function addFile($file_id)
	{
        $model = $this->getMorphClass();
        $ordination = FileRelation::where("ref_id",$this->id)->max('ordination')+1;
        return FileRelation::create([
            'file_model' => $model,
            'ref_id'     => $this->id,
            'file_id'    => $file_id,
            'ordination' => $ordination
        ]);
    }
    
    public function files()
    {
        return FileRelation::where("ref_id",$this->id)->orderBy("ordination");
    }

    public function removeFile($file_id)
    {
        return FileRelation::where("ref_id",$this->id)->delete();
    }

}
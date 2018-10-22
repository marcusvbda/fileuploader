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
        $ordination = FileRelation::where("model_id",$this->id)->where("model_type",$model)->max('ordination')+1;
        return FileRelation::create([
            'model_type' => $model,
            'model_id'   => $this->id,
            'file_id'    => $file->id,
            'ordination' => $ordination
        ]);
    }
    
    public function files()
    {
        $model = $this->getMorphClass();
        return FileRelation::where("model_id",$this->id)->where("model_type",$model)->orderBy("ordination");
    }

    public function removeFile(_File $file)
    {
        $model = $this->getMorphClass();
        return FileRelation::where("model_id",$this->id)->where("model_type",$model)->where("file_id",$file->id)->delete();
    }

    public function reorderFiles($rows)
    {
        $model = $this->getMorphClass();
        foreach ($rows as $row) 
        {
            FileRelation::where("model_id", $this->id)->where("model_type",$model)->where("file_id", $row["file_id"])->update($row);
        }
    }

}
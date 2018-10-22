<?php
namespace marcusvbda\uploader\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use marcusvbda\uploader\Models\File as _File;

class FileRelation extends Model
{
    protected $table = '_files_relation';
    protected $fillable = [
            'model_type',
            'model_id',
            'file_id',
            'ordination'
    ];

    public function file()
    {
        return $this->belongsTo(_File::class);
    }
        
}

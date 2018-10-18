<?php
namespace marcusvbda\uploader\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use marcusvbda\uploader\Models\File as _File;
use marcusvbda\uploader\Traits\HasFileRelation;


class FileCategory extends Model
{
    use  HasFileRelation;
    
        
    protected $table = '_files_categories';
    protected $fillable = [
            'id',
            'name'
    ];

    public function files()
	{
		return $this->belongsToMany(_File::class, '_files_categories_relation','_files_category_id','file_id');
	}
}

<?php
namespace marcusvbda\uploader\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use marcusvbda\uploader\Models\File as _File;
use Illuminate\Database\Eloquent\SoftDeletes;
use marcusvbda\uploader\Traits\HasFileRelashion;


class FileCategory extends Model
{
    use SoftDeletes, HasFileRelashion;
    
        
    protected $table = '_files_categories';
    protected $fillable = [
            'id',
            'name'
    ];
}

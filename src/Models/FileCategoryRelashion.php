<?php
namespace marcusvbda\uploader\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use marcusvbda\uploader\Models\File as _File;

class FileCategoryRelashion extends Model
{
    protected $table = '_files_categories_relashion';
    protected $fillable = [
            '_files_category_id',
            'file_id'
    ];

    public function file()
    {
        return $this->belongsTo(_File::class);
    }
}

<?php
namespace marcusvbda\uploader\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use marcusvbda\uploader\Models\File as _File;
use marcusvbda\uploader\Traits\HasFileRelation;


class AclCategory extends Model
{
    protected $table = '_acl_category';
    protected $fillable = [
            'user_id',
            'user_type',
            'file_category_id'
    ];

}

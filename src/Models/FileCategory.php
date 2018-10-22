<?php
namespace marcusvbda\uploader\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use marcusvbda\uploader\Models\File as _File;
use marcusvbda\uploader\Models\{AclCategory};
use marcusvbda\uploader\Traits\HasFileRelation;
use Auth;

class FileCategory extends Model
{
    use HasFileRelation;
    
        
    protected $table = '_files_categories';
    protected $fillable = [
            'id',
            'name'
    ];

    public function files()
	{
		return $this->belongsToMany(_File::class, '_files_categories_relation','file_category_id','file_id');
    }

    public function scopeAcl($query)
    {
		$can_categories = AclCategory::where("user_id",Auth::user()->id)->pluck("file_category_id")->ToArray();
		return $query->whereIn("id",$can_categories);
	}

    public function setAcl($userType,$userId)
    {
       return AclCategory::create([
           "user_type"  => $userType,
           "user_id"  => $userId,
           "file_category_id" => $this->id
       ]);
    }

    public function removeAcl($userType,$userId)
    {
        return AclCategory::where("user_type",$userType)->where("user_id",$userId)->delete();
    }
    

}

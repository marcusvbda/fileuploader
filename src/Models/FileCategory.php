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
            'name',
            'user_type',
            'user_id',
            'private'
    ];

    public function scopeVisible($query)
    {
		$user_type = str_replace( "\\", "\\\\", Auth::user()->getMorphClass());
		$user_id = Auth::user()->id;
		return $query->Where("private",0)->orWhereRaw("(private=1 and user_type='{$user_type}' and user_id='{$user_id}')");
    }
    
    public function scopePrivate($query)
    {
		$user_type = str_replace( "\\", "\\\\", Auth::user()->getMorphClass());
		$user_id = Auth::user()->id;
		return $query->Where("private",1)->where("user_type",$user_type)->where("user_id",$user_id);
	}

	public function scopePublic($query)
    {
		$user_type = str_replace( "\\", "\\\\", Auth::user()->getMorphClass());
		$user_id = Auth::user()->id;
		return $query->Where("private",0)->where("user_type",null)->where("user_id",null);
	}


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

    public function setPrivate()
	{
		$user_type = Auth::user()->getMorphClass();
		$user_id = Auth::user()->id;
		return $this->update(["user_type"=>$user_type,"user_id"=>$user_id,"private"=>1]);
	}

	public function setPublic()
	{
		return $this->update(["user_type"=>null,"user_id"=>null,"private"=>0]);
	}
    

}

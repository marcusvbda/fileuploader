<?php
namespace marcusvbda\uploader\Models;

use Eloquent;


use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\{Sluggable,SluggableScopeHelpers};
use marcusvbda\uploader\Models\FileRelation;
use Illuminate\Support\Facades\Storage;
use marcusvbda\uploader\Models\{FileCategory,AclCategory,FileCategoryRelation};
use Auth;

class File extends Model
{
	use Sluggable,SluggableScopeHelpers;
	
	protected $table = '_files';
	protected $appends = ['url','thumbnail'];

	protected $fillable = [
		'id',
		'name',
		'dir',
		'description',
		'extension',
		'size',
		'type',
		'slug',
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

	public function getThumbnailAttribute()
    {
		$url = config('uploader.image_server')."thumbnail/".$this->slug.".".$this->extension;
		return $this->attributes['thumbnail'] = $url;
	}
	
	public function getUrlAttribute()
    {
        $url = config('uploader.image_server').$this->slug.".".$this->extension;
		return $this->attributes['url'] = $url;
	}
    
    public function sluggable(){
		return
		[
			'slug' =>
			[
				'source' => 'name'
			]
		];
	}

	public function categories()
	{
		return $this->belongsToMany(FileCategory::class, '_files_categories_relation','file_id','file_category_id');
	}

	public function delete()
	{
		if(config('uploader.cascadeFile'))
		{
			FileRelation::where("file_id",$this->id)->delete();
			Storage::delete($this->dir);
			return parent::delete();
		}
		else
		{
			if( FileRelation::where("file_id",$this->id)->count()==0  )
			{
				Storage::delete($this->dir);
				return parent::delete();	
			}
		}
		return false;
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

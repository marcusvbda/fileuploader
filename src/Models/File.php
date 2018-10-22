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
	];

	public function scopeAcl($query)
    {
		$can_categories = AclCategory::where("user_id",Auth::user()->id)->pluck("file_category_id")->ToArray();
		$can_files = FileCategoryRelation::whereIn("file_category_id",$can_categories)->pluck("file_id")->ToArray();
		$no_category = $this::whereNotIn("id", FileCategoryRelation::pluck("file_id")->ToArray()  )->pluck("id")->ToArray();
		return $query->whereIn("id",$can_files)->orWhereIn("id",$no_category);
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
}

<?php
namespace marcusvbda\uploader\Models;

use Eloquent;


use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\{Sluggable,SluggableScopeHelpers};
use marcusvbda\uploader\Models\FileRelashions;
use Illuminate\Support\Facades\Storage;
use marcusvbda\uploader\Models\{FileCategory};


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

	public function getThumbnailAttribute()
    {
		
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
		return $this->belongsToMany(FileCategory::class, '_files_categories_relashion','file_id','_files_category_id');
	}

	public function delete()
	{
		if(config('uploader.cascadeFile'))
		{
			FileRelashions::where("file_id",$this->id)->delete();
			Storage::delete($this->dir);
			return parent::delete();
		}
		else
		{
			if( FileRelashions::where("file_id",$this->id)->count()==0  )
			{
				Storage::delete($this->dir);
				return parent::delete();	
			}
		}
		return false;
	}
}

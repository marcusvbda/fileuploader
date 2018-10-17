<?php
namespace marcusvbda\uploader\Models;

use Eloquent;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Cviebrock\EloquentSluggable\{Sluggable,SluggableScopeHelpers};
use marcusvbda\uploader\Models\FileRelashions;
use Illuminate\Support\Facades\Storage;
use marcusvbda\uploader\Models\{FileCategory};


class File extends Model
{
	use SoftDeletes,Sluggable,SluggableScopeHelpers;
	
    protected $table = '_files';
	protected $fillable = [
		'id',
		'name',
		'url',
		'extension',
		'size',
		'type',
		'slug',
    ];
    
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
			Storage::delete($this->url);
			return parent::delete();
		}
		else
		{
			if( FileRelashions::where("file_id",$this->id)->count()==0  )
			{
				Storage::delete($this->url);
				return parent::delete();	
			}
		}
		return false;
	}
}

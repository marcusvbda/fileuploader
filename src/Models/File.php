<?php
namespace marcusvbda\uploader\Models;

use Eloquent;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Cviebrock\EloquentSluggable\{Sluggable,SluggableScopeHelpers};



class File extends Model
{
	use SoftDeletes,Sluggable,SluggableScopeHelpers;
	
    protected $table = '_files';
	protected $fillable = [
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
}

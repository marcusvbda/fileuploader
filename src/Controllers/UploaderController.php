<?php
namespace marcusvbda\uploader\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use File;
use marcusvbda\uploader\Models\File as _File;
use Illuminate\Support\Facades\Storage;
use marcusvbda\uploader\Requests\UploadFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Intervention\Image\Facades\Image as Image;
use WebP;

class UploaderController extends Controller
{

    public function getFile($slug)
    {
        if ($file = _File::findBySlug($slug)) 
        {
            if (Storage::disk('local')->has($file->dir)) 
            {
                $path = storage_path('app/'.$file->dir);
                $response = response()->make(File::get(  $path  ));
                $response->header('content-type', File::mimeType($path));
                return $response;
            }
        }
    }

    public function getThumbnail($slug)
    {
        if ($file = _File::findBySlug($slug)) 
        {
            $thumbnailDir = config('uploader.thumbnail_path')."/".$file->id.".".$file->extension;
            if (Storage::disk('local')->has($thumbnailDir)) 
            {
                $path = storage_path('app/'.$thumbnailDir);
                $response = response()->make(File::get(  $path  ));
                $response->header('content-type', File::mimeType($path));
                return $response;
            }
        }
    }

    public static function edit(_File $file,$array)
    {
        if (isset($array["name"])) {
            $array["slug"] = SlugService::createSlug(_File::class, 'slug', $array["name"]);
            $array["dir"] = str_replace($file->slug, $array["slug"], $file->dir);
            Storage::move($file->dir, config('uploader.upload_path')."/". $array["slug"].".".$file->extension );
        }
        $file = $file->update($array);
        return $file;
    }


    public static function makeThumbnail(_File $file)
    {
        $path = storage_path("app/".$file->dir);
        $thumbnailDir = config('uploader.thumbnail_path')."/".$file->id.".".$file->extension;
        $thumb = Image::make( $path );
        $thumb = $thumb->resize(null,(int)config('uploader.thumbnail_height'), function ($constraint) 
        {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode($file->extension, 10);
        $thumbnail =  Storage::put($thumbnailDir, $thumb);
        
        return $thumbnail;
    }

    public static function upload($file,$name,$description)
    {
        try 
        {
            $path = config('uploader.upload_path');
            if(is_string($file))
            {
                $url = $file;
                $extension = pathinfo($url, PATHINFO_EXTENSION); 
                $filename  = pathinfo($url, PATHINFO_FILENAME);
                $filename  = pathinfo($url, PATHINFO_FILENAME);
                $type  = pathinfo($url, FILEINFO_MIME_TYPE);
                $slugname = SlugService::createSlug(_File::class, 'slug', $name);
                $data = file_get_contents($url);
                $dir = $path."/".$slugname.".".$extension;
                $buffer = file_get_contents($url);
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $type = $finfo->buffer($buffer);
                Storage::put($dir, $data);
            }
            else
            {
                $extension = $file->getClientOriginalExtension();
                $slugname = SlugService::createSlug(_File::class, 'slug', $name);
                $dir = $file->storeAs($path, $slugname.".".$extension);
                $type = substr($file->getMimeType(), 0, 5);
            }
            $type = explode("/",$type)[0];
            $newFile = [
                "name"       =>    $name,
                "dir"        =>    $dir,
                "description"=>    $description,
                "slug"   =>    $slugname,
                "extension"  =>    $extension,
                "type"       =>    $type
            ];
            return _File::create($newFile);
        }
        catch(\Exception $e)
        {
            Storage::delete($dir);
            return null;
        }
    }
}

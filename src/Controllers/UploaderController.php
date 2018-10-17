<?php
namespace marcusvbda\uploader\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use File;
use marcusvbda\uploader\Models\File as _Files;
use Illuminate\Support\Facades\Storage;
use marcusvbda\uploader\Requests\UploadFile;
use Cviebrock\EloquentSluggable\Services\SlugService;


class UploaderController extends Controller
{

    public function getFile($slug)
    {
        if ($file = _Files::findBySlug($slug)) 
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

    public static function upload($file,$filename,$description)
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
                $slugname = SlugService::createSlug(_Files::class, 'slug', $filename);
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
                $slugname = SlugService::createSlug(_Files::class, 'slug', $filename);
                $dir = $file->storeAs($path, $slugname.".".$extension);
                $type = substr($file->getMimeType(), 0, 5);
            }
            $newFile = [
                "name"       =>    $filename,
                "dir"        =>    $dir,
                "description"=>    $description,
                "slug"   =>    $slugname,
                "extension"  =>    $extension,
                "type"       =>    $type
            ];
            return _Files::create($newFile);
        }
        catch(\Exception $e)
        {
            Storage::delete($dir);
            return null;
        }
    }
}

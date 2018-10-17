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



    public static function upload($file,$filename)
    {
        try 
        {
            $path = config('uploader.upload_path');
            $extension = $file->getClientOriginalExtension();
            $slugname = SlugService::createSlug(_Files::class, 'slug', $filename);
            $dir = $file->storeAs($path, $slugname.".".$extension);
            $newFile = [
                "name"       =>    $filename,
                "dir"        =>    $dir,
                "filename"   =>    $slugname,
                "extension"  =>    $extension,
                "size"       =>    $file->getClientSize(),
                "type"       =>    substr($file->getMimeType(), 0, 5)
            ];
            return _Files::create($newFile);
        }
        catch(\Exception $e)
        {
            return null;
        }
    }
}

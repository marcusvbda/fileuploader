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

use App\Teste;

class UploaderController extends Controller
{

    public function getFile($slug)
    {

        if ($file = _Files::findBySlug($slug)) 
        {
            if (Storage::disk('local')->has($file->url)) 
            {
                $path = storage_path('app/'.$file->url);
                $response = response()->make(File::get(  $path  ));
                $response->header('content-type', File::mimeType($path));
                return $response;
            }
        }
    }

    public function teste2()
    {
        $teste = Teste::first();
        $teste->removeFile(1);
        dd($teste);
    }

    public function testeUpload(Request $request)
    {
        $data = $request->all();
        $result = UploaderController::upload($data["_file"],$data["name"]);
        dd($result);
    }

    public static function upload($file,$filename)
    {
        try 
        {
            $path = config('uploader.upload_path');
            $extension = $file->getClientOriginalExtension();
            $slugname = SlugService::createSlug(File::class, 'slug', $filename);
            $url = $file->storeAs($path, $slugname.".".$extension);
            $newFile = [
                "name"       =>    $filename,
                "url"        =>    $url,
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
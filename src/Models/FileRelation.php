<?php
namespace marcusvbda\uploader\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use marcusvbda\uploader\Models\File as _File;

class FileRelation extends Model
{
        
    protected $table = '_files_relation';
    protected $fillable = [
            'file_model',
            'ref_id',
            'file_id',
            'ordination'
    ];

    public function file()
    {
        return $this->belongsTo(_File::class);
    }
        
    public function reorder($rows)
    {
        foreach ($rows as $row) {
            $this::where("ref_id", $this->ref_id)->where("file_id", $row["file_id"])->update($row);
        }
    }
}

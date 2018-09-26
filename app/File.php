<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $primaryKey= 'id';
    protected $fillable = [
    	'file_name',
	    'size',
	    'unit',
	    'file_path',
	    'folder_id'
    ];

    public function Folder(){
    	return $this->belongsTo('App\Folder', 'folder_id');
    }
}

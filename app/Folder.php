<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
    	'name',
	    'user_id',
	    'size',
	    'unit'
    ];
    protected $parent_folder = 'parent_folder';
    protected $size = 'size';

    public function User(){
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function File(){
    	return $this->hasMany('App\File');
    }

    public function Parent(){
		return $this->hasMany('App\Folder', 'parent_folder');
    }

    public function Child(){
		return $this->belongsTo('App\Folder', 'parent_folder');
    }

}

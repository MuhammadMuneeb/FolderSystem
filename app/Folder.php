<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
    	'name',
	    'size'
    ];

    public function User(){
    	return $this->belongsTo('App\User', 'user_id');
    }
	
}

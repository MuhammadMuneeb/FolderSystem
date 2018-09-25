<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function create(Request $request, $folder_id){
		$file = $request->file('file');
		$file_path = $file->move(public_path(Auth::id()), $file->getClientOriginalName());
		$file_size = filesize($file);
		File::create([
			'file_name'=>$file->getClientOriginalName(),
			'size'=>$file_size,
			'file_path'=>$file_path,
			'folder_id'=>$folder_id
		]);
	    $files = File::where('folder_id', $folder_id)->get();
	    return response()->json($files, 200);
    }

    public function download(){

    }

    public function rename(Request $request, $id){
	    File::where('id', $id)->update(['name'=>$request['name']]);
	    $file = File::where('id', $id)->first();
	    return response()->json($file, 200);
    }

    public function delete($file_id){
    	$folder_id = File::where('id', $file_id)->value('folder_id');
		File::where('id', $file_id)->delete();
	    $files = File::where('folder_id', $folder_id)->get();
	    return response()->json($files, 200);
    }

    public function display($folder_id){
		$files = File::where('folder_id', $folder_id)->get();
		return response()->json($files, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function create_folder(Request $request){
    	$data = $request->all();
    	$folder = new Folder();
    	$folder->name = $request['name'];
	    $folder->user_id = Auth::id();
	    $folder->size = 0;
    	$folder->save();
    	$this->folder_list();
		$folders = Folder::where('user_id', Auth::id())->get();
    	return response()->json($folders, 200);
    }

    public function delete_folder($id){
    	Folder::with('File')->where('id', $id)->delete();
	    $folders = Folder::where('user_id', Auth::id())->get();
	    return response()->json($folders, 200);
    }

    public function edit_name(Request $request, $id){
    	Folder::where('id', $id)->update(['name'=>$request['name']]);
    	$folder = Folder::where('id', $id)->first();
    	return response()->json($folder, 200);
    }

    public function folder_list(){
    	$folders = Folder::where('user_id', Auth::id())->get();
        return view('folders')->with(compact('folders'));
    }

}

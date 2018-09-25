<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function create_folder(Request $request){
    	$data = $request->all();
    	$folder = new Folder($data);
    	$folder->save();

    	return response()->json('Folder created', 200);
    }

    public function delete_folder($id){
    	Folder::where('id', $id)->delete();
    	return response()->json('Folder deleted', 200);
    }

    public function edit_name(Request $request, $id){
    	Folder::where('id', $id)->update(['name'=>$request['name']]);
    	return response()->json('Folder name updated', 200);
    }

    public function folder_list(){
    	$folders = Folder::where('user_id', Auth::id())->get();
    	return response()->json($folders, 200);
    }

}

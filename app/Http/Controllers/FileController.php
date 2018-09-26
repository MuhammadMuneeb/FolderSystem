<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\Http\Requests\FileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller {

	public function create( FileRequest $request, $folder_id ) {

		$file      = $request->file( 'file' );
		$file_path = $file->move( public_path( Auth::id() ), $file->getClientOriginalName() );
		$file_size = $request['size'];
		$size      = [ $file_size, 0 ];
		if ( $file_size >= 1024 ) {
			$size = $this->size_cal( $file_size, 0 );
		}
		$unit = '';
		if ( $size[1] == 0 ) {
			$unit = 'bytes';
		} elseif ( $size[1] == 1 ) {
			$unit = 'KBs';
		} elseif ( $size == 2 ) {
			$unit = 'MBs';
		} elseif ( $size == 3 ) {
			$unit = 'GBs';
		}
		File::create( [
			'file_name' => $file->getClientOriginalName(),
			'size'      => $size[0],
			'unit'      => $unit,
			'file_path' => $file_path,
			'folder_id' => $folder_id
		] );

		$current_folder = Folder::where( 'id', $folder_id )->value( 'size' );
		Folder::where( 'id', $folder_id )->update( [ 'size' => $current_folder + $size[0], 'unit' => $unit ] );
		$files = File::where( 'folder_id', $folder_id )->get();

		return response()->json( $files, 200 );
	}

	public function rename( Request $request, $id ) {
		File::where( 'id', $id )->update( [ 'file_name' => $request['name'] ] );
		$file = File::where( 'id', $id )->first();

		return response()->json( $file, 200 );
	}

	public function delete( $file_id ) {
		$folder_id = File::where( 'id', $file_id )->value( 'folder_id' );
		$file_size = File::where( 'id', $file_id )->value( 'size' );
		$file_unit = File::where( 'id', $file_id )->value( 'unit' );
		File::where( 'id', $file_id )->delete();
		$files = File::where( 'folder_id', $folder_id )->get();

		return response()->json( $files, 200 );
	}

	public function display( $folder_id ) {
		$files = Folder::with( 'File' )->where( 'id', $folder_id )->get();

		return response()->json( $files, 200 );
	}

	public function size_cal( $size, $unit ) {
		$s = $size / 1024;
		$unit ++;
		if ( $s >= 1024 ) {
			$this->size_cal( $s, $unit );
		}

		return [ $s, $unit ];

	}
}

<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
	    $folders = Folder::where('user_id', Auth::id())->where('parent_folder', null)->get();
	    return view('folders')->with(compact('folders'));
    }
}

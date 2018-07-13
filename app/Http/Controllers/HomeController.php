<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Profile;
use App\user;
use App\Post;
use Auth;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $profiles = DB::table('users')
                    ->join('profiles','users.id','=','profiles.user_id')
                    ->select('users.*','profiles.*')
                    ->where(['profiles.user_id' => $user_id])
                    ->first();
         $posts = POst::paginate(3);        
        return view('home',['profile' => $profiles,'posts'=>$posts]);
    }
}

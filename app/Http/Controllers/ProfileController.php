<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use App\Profile;
use Auth;
class ProfileController extends Controller
{
    //
    public function profile()
    {
        return view('Profile.profile');
    }

    public function addProfile(REQUEST $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'designation' => 'required',
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif'
       ]);
      $profile= new Profile;
       $profile->name= $request->input('name');
       $profile->user_id=Auth::user()->id;
       $profile->designation = $request->input('designation');
      /* if(Input::hasFile('profile_pic'))
       {
          $file = Input::file('profile_pic');
          $file->move(public_path(). '/uploads/'. $file->
               getClientoriginalName());
               $url = URL::to("/") .'/uploads/'. $file->
               getClientoriginalName();
               $profile->Profiel_pic = $url;
               return $url;
       }*/
       $profile->Profiel_pic='';
       if ($request->hasFile('profile_pic')) {
        $image = $request->file('profile_pic');
        $name = $image->
        getClientoriginalName();
        $destinationPath = public_path('/images');
        $image->move($destinationPath, $name);
        $profile->Profiel_pic =URL::to("/").'/images'.'/'.$name;
    
    }
      
       $profile->save();

     return redirect('/home')->with('response', 'Profile Added Succesfully');
   

    }
}




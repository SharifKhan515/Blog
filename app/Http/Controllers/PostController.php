<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Auth;
use App\Post;
use App\Like;
use App\Dislike;
use App\Category;
use App\Comment;
use App\Profile;
use DB;


class PostController extends Controller
{
    //
    public function post()
    {
        $category = Category::all();
       
      
    
        return  view('post.post',[ 'category' => $category]);
    }

    public function addPost(REQUEST $request)
    {
        $this->validate($request, [
            'post_title' => 'required',
            'post_body' => 'required',
            'category_id' => 'required',
            'post_image' => 'required|image|mimes:jpeg,png,jpg,gif'
       ]);

       $post = new Post;
       $post->post_title= $request->input('post_title');
       $post->user_id=Auth::user()->id;
       $post->post_body = $request->input('post_body');
       $post->category_id = $request->input('category_id');
    
        $post->post_image='';
       if ($request->hasFile('post_image')) {
        $image = $request->file('post_image');
        $name = $image->
        getClientoriginalName();
        $destinationPath = public_path('/posts');
        $image->move($destinationPath, $name);
        $post->post_image =URL::to("/").'/posts'.'/'.$name;
    
    }
 
      
   $post->save();

  return redirect('/home')->with('response', 'Post Added Succesfully');

    }
   public function view($post_id)
   {
    $post = Post::where('id','=',$post_id)->get();
    $category = Category::all();
   // $likePost = Post::find($post_id);
    $likeCnt = Like::where('post_id','=',$post_id)->count();
    $dislikeCnt = Dislike::where('post_id','=',$post_id)->count();
   // $Comments = Comments::where('post_id','=',$post_id)->get();


    $comments = DB::table('users')
    ->join('comments', 'users.id', '=', 'comments.user_id')
    ->join('posts','comments.post_id','=','posts.id')
    ->select( 'users.name','comments.*' )
    ->where(['posts.id'=>$post_id])
    ->get();
    //return $comments;
    //exit();
    return view('post.view',['posts'=>$post,'category' => $category,'likecnt'=>$likeCnt,'dislikecnt'=>$dislikeCnt,'comment'=>$comments]);


   }

   public function edit($post_id)
   {
    $category = Category::all();
    $post = Post::find($post_id);

   //$cat=$post->category_id;
  
    $categories = Category::find($post->category_id);
    
    return view('post.edit',['post'=>$post,'category' => $category,'categories'=>$categories]);
   }

   public function editPost(REQUEST $request,$post_id)
   {
    $this->validate($request, [
        'post_title' => 'required',
        'post_body' => 'required',
        'category_id' => 'required',
        'post_image' => 'required|image|mimes:jpeg,png,jpg,gif'
   ]);

   $post = new Post;
   $post->post_title= $request->input('post_title');
   $post->user_id=Auth::user()->id;
   $post->post_body = $request->input('post_body');
   $post->category_id = $request->input('category_id');

    $post->post_image='';
   if ($request->hasFile('post_image')) {
    $image = $request->file('post_image');
    $name = $image->
    getClientoriginalName();
    $destinationPath = public_path('/posts');
    $image->move($destinationPath, $name);
    $post->post_image =URL::to("/").'/posts'.'/'.$name;

}

  $data = array(
    'post_title'=> $post->post_title,
    'user_id'=> $post->user_id,
    'post_body'=> $post->post_body,
    'category_id'=> $post->category_id,
    'post_image'=> $post->post_image,

  );
          Post::where('id',$post_id)->update($data);
$post->update($data);

    return redirect('/home')->with('response', 'Post Updated Succesfully');


   }

   public function deletePost($post_id)
   {
       Post::where('id',$post_id)->delete();

       return redirect('/home')->with('response', 'Post deleted Succesfully');
   }
  
   public function category($cat_id)
   {
       $category = Category::all();
       $posts = DB::table('categories')
       ->join('posts', 'posts.category_id', '=', 'categories.id')
       ->select( 'categories.*','posts.*' )
       ->where(['categories.id'=>$cat_id])
       ->get();
       //return $posts;
       //exit();
       return view('Category.categoriespost',['category'=>$category,'post'=>$posts]);
   }

   public function like($post_id)
   {
    $loggedin_user = Auth::user()->id;
    $like_user = Like::where(['user_id'=>$loggedin_user,'post_id'=>$post_id])->first();

    if(empty($like_user->user_id))
    {
        $user_id = Auth::user()->id;
        $email = Auth::user()->email;
        $post_id= $post_id;
        $like =new like;
        $like->user_id = $user_id;
        $like->email = $email;
        $like->post_id = $post_id;
        $like->save();

        return redirect("/view/{$post_id}");

    }
    else
      {
        return redirect("/view/{$post_id}"); 
      }


   }
   public function dislike($post_id)
   {
    $loggedin_user = Auth::user()->id;
    $dislike_user = Dislike::where(['user_id'=>$loggedin_user,'post_id'=>$post_id])->first();

    if(empty($dislike_user->user_id))
    {
        $user_id = Auth::user()->id;
        $email = Auth::user()->email;
        $post_id= $post_id;
        $dislike =new Dislike;
        $dislike->user_id = $user_id;
        $dislike->email = $email;
        $dislike->post_id = $post_id;
        $dislike->save();

        return redirect("/view/{$post_id}");

    }
    else
      {
        return redirect("/view/{$post_id}"); 
      }


   }

   public function comment(REQUEST $request,$post_id)
   {
    $this->validate($request, [
        'comment' => 'required',  
   ]);
  $comment = new Comment;
  $comment->user_id = Auth::user()->id;
  $comment->post_id =$post_id;
  $comment->comment = $request->input('comment');
  $comment->save();
  return redirect("/view/{$post_id}")->with('response', 'comment added Succesfully');;
  
   }

   public function search(REQUEST $request)
   {
       $user_id = Auth::user()->id;
       $profile = Profile::where('user_id',$user_id)->first();
       $keyword = $request->input('search');
       $post = Post::where('post_title','LIKE','%'.$keyword.'%')->get();
       //return $profile->Profiel_pic;
       //exit();

     return view('post.searchposts',['profile'=>$profile,'posts'=>$post]);
      
   }
}

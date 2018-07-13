@extends('layouts.app')
<style type="text/css">
    
    .post_img{
        border:50%;
        max-width:200px;
        left:20% ;  
    }
    </style>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
                
                @if(session('response'))
                <div class="alert alert-success">{{session('response')}}</div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">View Post</div>

                <div class="panel-body">
                    <div class="col-md-4" >
                       <ul class="list-group">
                           @if(count($category)>0)
                           @foreach($category->all() as $category)

                           <li class="list-group-item"><a href='{{url("category/{$category->id}")}}'>{{$category->category}}</a> </li>
                           @endforeach

                           @else
                             <p>No category found</p>
                           @endif
                    </ul> 

                    </div>
                    <div class="col-md-8" >
                        @if(count($posts)>0)
                           @foreach($posts->all() as $post)
                            <h4>{{ $post->post_title}}</h4><br/>
                            <img src="{{$post->post_image}}"  class="post_img" alt="">
                            <br/>
                            <p>{{$post->post_body}}</p>

                            <ul class="nav nav-pills">
                                <li role="presentation">
                                    <a href='{{url("/like/{$post->id}")}}'>
                                        <span class=" fas fa-eye"> like({{$likecnt}})</span>
                                       
                                    </a>
                                   
                                   
                                </li>
                                <li role="presentation">
                                    
                                    <a href='{{url("/dislike/{$post->id}")}}'>
                                        <span class=" fas fa-eye">Dislike({{$dislikecnt}})</span>
                                       
                                    </a>

                                
                                </li>
                                <li role="presentation">
                                    
                                    <a href='{{url("/comment/{$post->id}")}}'>
                                        <span class=" fas fa-eye">Comment( {{count($comment)}} )</span>
                                       
                                    </a>

                    
                                </li>

                            </ul>
                            
                           @endforeach
                        @else
                        <p>No Post Available</p>
                        @endif
                      <form action="{{url("/comment/{$post->id}")}}" method="post">
                      {{csrf_field()}}
                      <div class="form-group">
                          <textarea id="comment" rows="6" class="form-control" name="comment"
                          required autofocus></textarea>
                      </div>
                      <div class="form-group">
                          <button type="submit" class="btn btn-success btn-lg btn-block">Post
                              Comment</button>

                      </div>
                      </form>
                      <h3>Comments</h3>
                      <hr/>
                      @if(count($comment)>0)
                      @foreach($comment->all() as $comment)
                         <p>{{ $comment->comment}}</p>
                         <p>Commented by: {{ $comment->name}}</p>
                         <hr/>
                      @endforeach
                      @else

                      @endif

                    </div>

                    </div>

                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

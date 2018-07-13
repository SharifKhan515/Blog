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
                        @if(count($post)>0)
                           @foreach($post->all() as $post)
                            <h4>{{ $post->post_title}}</h4><br/>
                            <img src="{{$post->post_image}}"  class="post_img" alt="">
                            <br/>
                            <p>{{substr($post->post_body,0,150)}}</p>

                            <ul class="nav nav-pills">
                                <li role="presentation">
                                    <a href='{{url("/view/{$post->id}")}}'>
                                        <span class=" fas fa-eye"> View</span>
                                       
                                    </a>
                                   
                                   
                                </li>
                                <li role="presentation">
                                    
                                    <a href='{{url("/edit/{$post->id}")}}'>
                                        <span class=" fas fa-eye">Edit</span>
                                       
                                    </a>

                                
                                </li>
                                <li role="presentation">
                                    
                                    <a href='{{url("/delete/{$post->id}")}}'>
                                        <span class=" fas fa-eye">Delete</span>
                                       
                                    </a>

                    
                                </li>

                            </ul>
                            <cite style="float-left">Posted on:{{date('M j, Y H:i',strtotime($post->updated_at))}}</cite>
                            <hr/>
                            
                           @endforeach
                        @else
                        <p>No Post Available</p>
                        @endif

                    </div>

                    </div>

                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

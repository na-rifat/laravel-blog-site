@extends('layouts.app')

@section('content')
   <h1>Posts</h1>  
    @if(count($posts) > 0)
        <div class="card"><ul class="list-group list-group-flush">                      
            @foreach($posts as $post)                                
                <div class="row" >
                    <div class="col-md-2" >
         
                        {{-- <img  style="width: 150px; " src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/480px-No_image_available.svg.png" alt="cover_image"> --}}
                    <img  style="width: 200px; height: 100px;" src="data: image/jpeg; base64, {{base64_encode(utf8_decode($post->cover_image))}}" alt="cover_image">
                    </div>
                    <div class="row-md-8">
                        <h3><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                        <small>Written on {{$post->created_at}}</small>
                    </div>
                    
                </div>
               
            @endforeach
        </ul></div>   
    @endif
@endsection
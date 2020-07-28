@extends('layouts.app')

@section('content')
<a href="/posts" class="btn btn-default">Go back</a>
<h1>{{$post->title}}</h1>
<div class="row">
    <div class="row-md-12">
        <img  style="width: 100%;" src="data: image/jpeg; base64, {{$post->cover_image}}" alt="cover_image">
    </div>
</div>
<p>{{$post->body}}</p>
<hr>
<small>Written on {{$post->created_at}}</small>
<hr>
@if(!Auth::guest() && Auth::user()->id == $post->user_id)
    <a href="\posts\{{$post->id}}\edit" class="btn btn-default">Edit</a>
    {{-- delete --}}
    {!!Form::open(['action' => ['PostController@destroy', $post->id], 'method' => 'POST'])!!}
        {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {{Form::hidden('_method', 'DELETE')}}
    {!!Form::close()!!}
@endif
@endsection
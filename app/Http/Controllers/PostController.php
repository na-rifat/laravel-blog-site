<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Storage;
use App\Post;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use DB;

DB::statement("CREATE TABLE IF NOT EXISTS posts(      
    id serial PRIMARY KEY NOT NULL,
    title VARCHAR(500),
    body VARCHAR(500),
    user_id INTEGER,
    cover_image VARCHAR( 10485760),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);");

// DB::statement("CREATE TABLE IF NOT EXISTS posts(      
//     id INT(5) UNSIGNED PRIMARY KEY NOT NULL,
//     title VARCHAR(500),
//     body VARCHAR(500),
//     user_id INT(5),
//     cover_image LONGBLOB,
//     created_at TIMESTAMP default current_timestamp,
//     updated_at TIMESTAMP default current_timestamp
// );");

function file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
     return mb_convert_encoding($content, 'UTF-8',
         mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}
class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts=Post::all();
        
       
        // $post=DB::select('SELECT * FROM posts ORDER BY id DESC;');
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
        
        // handle the file upload
        if($request->hasFile('cover_image')){
             // Get the file from the request
            $file = $request->file('cover_image')->path();
            // $icontents=file_get_contents_utf8($request->file('cover_image'));
            // Get the contents of the file
            // $contents = $file->openFile()->fread($file->getSize());

             // Get the file from the request
    $file = $request->file('cover_image');

    // Get the contents of the file
    $icontents = $file->openFile()->fread($file->getSize());
           // $icontents =base64_encode(utf8_encode(file_get_contents($request->file('cover_image')->pat‌​h())));//utf8_encode(file_get_contents($request->file('cover_image')));
            // Get the contents of the file
            //$contents = $file->openFile()->fread($file->getSize());            
        }else{
            $icontents = 'noimage.png';
        }
       // DB::insert('insert into posts (title, body, user_id, cover_image) values (?, ?, ?, ?)', [$request->title, $request->body, auth()->user()->id, $icontents]);
        $post = new Post;
        $post->title=$request->input('title');
        $post->body=$request->input('body');
        $post->user_id=auth()->user()->id;
        $post->cover_image =$icontents;
        $post->save();
       

        return redirect("/posts")->with('success', 'Post created.');
    }
 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post=Post::find($id);       
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post=Post::find($id);      
        if(auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unautorized page :)');
        }
        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
        
        // handle the file upload
        if($request->hasFile('cover_image')){
            //get filename with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //get just filename
            $filename=pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }

        $post = Post::find($id);
        $post->title=$request->input('title');
        $post->body=$request->input('body');   
        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect("/posts")->with('success', 'Post updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post=Post::find($id);
        if($post->user_id !== Auth()->user()->id){
            return redirect('/posts')->with('error', 'Couldn\'t delete this item');
        }
        if($post->cover_image != 'noimage.png'){
            Storage::delete('public/cover_images/'.$post->cover_image);
        }
        $post->delete();
        return redirect('/posts')->with('success', 'Post removed.');        
    }
}

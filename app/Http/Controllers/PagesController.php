<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    function index(){
        
        $title='Home';
        return view('pages.index')->with('title', $title);
    }
    function services(){
        $services=array(
            'title' => 'Services',
            'services' => ['Web design', 'Full stack web developer', 'Desktop based applications']
        );        
        return view('pages.services')->with($services);
    }
    function about(){
        $title='About';
        return view('pages.about')->with('title', $title);
    }
}

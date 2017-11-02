<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class CreatePostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content'   =>  'required'
        ]);

        //instanciando a post
        $post = new Post($request->all());

        //asignamos el post al usuario que está creando el post
        auth()->user()->posts()->save($post);

        return $post->title;
    }

}

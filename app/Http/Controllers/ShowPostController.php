<?php

namespace App\Http\Controllers;

use App\Post;

class ShowPostController extends Controller
{

    public function __invoke(Post $post, $slug)
    {
        //abort_unless($post->slug == $slug, 404);

        if($post->slug != $slug){
            return redirect($post->url, 301);
        }

        return view('posts.show', compact('post'));
    }

}

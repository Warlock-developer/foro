<?php


use App\Post;

class PostListTest extends FeatureTestCase
{

    function test_a_user_can_see_the_post_list_and_go_to_the_details()
    {
        $post = $this->createPost([
           'title' => '¿Debo usar Laravel 5.3 o 5.1 LTS'
        ]);

        $this->visit('/')
            ->seeInElement('h1', 'Posts')
            ->see($post->title)
            ->click($post->title)
            ->seePageIs($post->url);

    }


    function test_the_post_are_paginated()
    {

        //having....
        $first = factory(Post::class)->create([
           'title' => 'El primer post'
        ]);

        factory(Post::class)->times(15)->create();

        $last = factory(Post::class)->create([
            'title' => 'El ultimo post'
        ]);

        //when
        $this->visit('/')
            ->see($first->title)
            ->dontSee($last->title)
            ->click('2')
            ->see($last->title)
            ->dontSee($first->title);

    }
}

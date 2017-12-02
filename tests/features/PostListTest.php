<?php


use App\Category;
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


    function test_a_user_can_see_posts_filtered_by_category()
    {

        $laravel = factory(Category::class)->create([
            'name' => 'Laravel', 'slug' => 'laravel'
        ]);

        $vue = factory(Category::class)->create([
            'name' => 'Vue.js', 'slug' => 'vue-js'
        ]);

        $laravelPost = factory(Post::class)->create([
            'title' => 'Post de Laravel',
            'category_id' => $laravel->id
        ]);

        $vuePost = factory(Post::class)->create([
            'title' => 'Post de Vue.js',
            'category_id' => $vue->id
        ]);


        $this->visit('/')
            ->see($laravelPost->title)
            ->see($vuePost->title)
            ->within('.categories', function() {
                $this->click('Laravel');
            })
            ->seeInElement('h1', 'Post de Laravel')
            ->see($laravelPost->title)
            ->dontSee($vuePost->title);


    }


    function test_a_user_can_see_posts_filtered_by_status()
    {

        $pendingPost = factory(Post::class)->create([
            'title' => 'Post pendiente',
            'pending' => true,
        ]);

        $completedPost = factory(Post::class)->create([
            'title' => 'Post completado',
            'pending' => false
        ]);

        $this->visitRoute('posts.pending')
            ->see($pendingPost->title)
            ->dontSee($completedPost->title);

        $this->visitRoute('posts.completed')
            ->see($completedPost->title)
            ->dontSee($pendingPost->title);

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

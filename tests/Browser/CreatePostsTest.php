<?php

namespace Tests\Browser;

use App\Category;
use App\Post;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestsHelper;

class CreatePostsTest extends DuskTestCase
{
    use DatabaseMigrations, TestsHelper;

    protected $title = 'Esta es una pregunta';
    protected $content = 'Este es el contenido';

    //prueba que un usuario pueda crear un post
    function test_a_user_create_a_post()
    {
        $user = $this->defaultUser();

        $category = factory(Category::class)->create();


        $this->browse(function($browser) use ($user, $category){
            //Having campos a ser guardados
            $browser->loginAs($user)
                ->visitRoute('posts.create')
                ->type('title', $this->title)
                ->type('content',$this->content)
                ->select('category_id', (string) $category->id)
                ->press('Publicar')
                //test a user is redirected to the posts details after creating it
                ->assertPathIs('/posts/1-esta-es-una-pregunta');

        });


        //Then
        $this->assertDatabaseHas('posts',[
            'title' => $this->title,
            'content' => $this->content,
            'pending' => true,
            'user_id' => $user->id,
        ]);

        $post = Post::first();

        //Test the authos is suscribed automatically to the post.
        $this->assertDatabaseHas('subscriptions',[
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);


    }

    function test_creating_a_post_requires_authentication()
    {
        $this->browse(function ($browser) {

            $browser->visitRoute('posts.create')
                ->assertRouteIs('token');
        });

    }

    //validación del formulario
    function test_create_post_form_validation()
    {
        $this->browse(function ($browser) {

            $browser->loginAs($this->defaultUser())
                    ->visitRoute('posts.create')
                    ->press('Publicar')
                    ->assertRouteIs('posts.create')
                    ->assertSeeErrors([
                        'title' => 'El campo título es obligatorio',
                        'content' => 'El campo contenido es obligatorio'
                    ]);

        });

    }

}

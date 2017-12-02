<?php

use App\Post;

class CreatePostsTest extends FeatureTestCase
{
    //prueba que un usuario pueda crear un post
    function test_a_user_create_a_post()
    {
        //Having campos a ser guardados
        $title = 'Esta es una pregunta';
        $content = 'Este es el contenido';

        //simular que un usuario está conectado
        $this->actingAs($user = $this->defaultUser());

        $category = factory(\App\Category::class)->create();


        //When
        $this->visit(route('posts.create'))
            ->type($title,'title')
            ->type($content,'content')
            ->select($category->id, 'category_id')
            ->press('Publicar');

        //Then
        $this->seeInDatabase('posts',[
           'title' => $title,
            'content' => $content,
            'pending' => true,
            'user_id' => $user->id,
            'slug' => 'esta-es-una-pregunta',
            'category_id' => $category->id,
        ]);

        $post = Post::first();

        //Test the authos is suscribed automatically to the post.
        $this->seeInDatabase('subscriptions',[
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        //test a user is redirected to the posts details after creating it
        $this->seePageIs($post->url);

    }

    function test_creating_a_post_requires_authentication()
    {
        $this->visit(route('posts.create'))
            ->seePageIs(route('token'));
    }

    //validación del formulario
    function test_create_post_form_validation()
    {
        $this->actingAs($this->defaultUser())
            ->visit(route('posts.create'))
            ->press('Publicar')//sin completar nungun campo
            ->seePageIs(route('posts.create'))
            ->seeErrors([
                'title' => 'El campo título es obligatorio',
                'content' => 'El campo contenido es obligatorio'
            ]);

    }

}
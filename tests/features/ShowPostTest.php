<?php


class ShowPostTest extends FeatureTestCase
{

    function test_a_user_can_see_the_post_details()
    {
        //Having
        $user = $this->defaultUser([
            'first_name'  =>  'Dawin',
            'last_name' => 'Valenzuela'
        ]);


        $post = $this->createPost([
            'title' =>  'Este es el titulo del post',
            'content'   =>  'Este es el contenido del post',
            'user_id'   =>  $user->id,
        ]);

        //$user->posts()->save($post);

        //when
        $this->visit($post->url)
            ->seeInElement('h1', $post->title)
            ->see($post->content)
            ->see('Dawin Valenzuela');

    }


    function test_old_urls_are_redirected()
    {

        //Having
        $post = $this->createPost([
            'title' =>  'Old title',
        ]);

        $url = $post->url;

        $post->update(['title' => 'New title']);

        $this->visit($url)
            ->seePageIs($post->url);

    }


    //prueba de regresion para exponer una caracteristica o inconveniente
    /*public function test_post_url_with_wrong_slugs_still_work()
    {
        //Having
        $user = $this->defaultUser();

        $post = factory(\App\Post::class)->make([
            'title' =>  'Old title',
        ]);

        $user->posts()->save($post);


        $url = $post->url;

        $post->update(['title' => 'New title']);

        $this->visit($url)
            ->assertResponseOk()
            ->see('New title');

        $this->get($url)
            ->assertResponseStatus(404);

    }*/
}

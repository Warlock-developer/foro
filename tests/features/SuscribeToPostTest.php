<?php


use App\User;

class SuscribeToPostTest extends FeatureTestCase
{

    public function test_a_user_can_suscribe_to_a_post()
    {
        //creamos post
        $post = $this->createPost();

        //creamos usuario que no sea el autor del post
        $user = factory(User::class)->create();

        $this->actingAs($user);

        //when
        $this->visit($post->url)
            ->press('Suscribirse al post');


        //then
        $this->seeInDatabase('subscriptions',[
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        $this->seePageIs($post->url)
            ->dontSee('Suscribirse al post');

    }


    function test_a_user_can_unsubscribe_from_a_post()
    {
        //Having
        $post = $this->createPost();

        $user = factory(User::class)->create();

        $user->subscribeTo($post);//comprobamos si esta subscrito a un post

        $this->actingAs($user);

        //when
        $this->visit($post->url)
            ->dontSee('Suscribirse al post')
            ->press('Desuscribirse del post');

        $this->dontSeeInDatabase('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->seePageIs($post->url);

    }

}

<?php

use App\Notifications\PostCommented;
use App\User;
use Illuminate\Support\Facades\Notification;

class NotifyUsersTest extends FeatureTestCase
{

    public function test_the_subscribers_receive_a_notification_when_post_id_commented()
    {
        Notification::fake();

        $post = $this->createPost();

        $suscriber = factory(User::class)->create();

        $suscriber->subscribeTo($post);

        $writer = factory(User::class)->create();

        $writer->subscribeTo($post);

        $comment = $writer->comment($post, 'Un comentario cualquiera');

        //notification
        Notification::assertSentTo(
            $suscriber, PostCommented::class, function($notification) use($comment){
            return $notification->comment->id == $comment->id;
        });

        //no enviar la notificación a un usuario en particular
        //the author of the comment shouldn't be notified even if he or she is a subscriber
        Notification::assertNotSentTo($writer, PostCommented::class);

    }
}

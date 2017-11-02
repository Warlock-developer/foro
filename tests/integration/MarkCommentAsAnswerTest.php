<?php


use App\Comment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MarkCommentAsAnswerTest extends TestCase
{

    use DatabaseTransactions;//traid

    function test_a_post_can_be_aswered()
    {
        $post = $this->createPost();

        $comment = factory(Comment::class)->create([
            'post_id' => $post->id
        ]);

        $comment->markAsAnswer();

        $this->assertTrue($comment->fresh()->answer);//verificar si la respuesta del post es verdadera

        $this->assertFalse($post->fresh()->pending);

    }

    function test_a_post_can_only_have_one_answere()
    {
        $post = $this->createPost();

        //creamos 2 posts
        $comments = factory(Comment::class)->times(2)->create([
            'post_id' => $post->id
        ]);



        $comments->first()->markAsAnswer();
        $comments->last()->markAsAnswer();

        $this->assertFalse($comments->first()->fresh()->answer);

        $this->assertTrue($comments->last()->fresh()->answer);


    }
}

<?php

use App\{Token,User};
use Symfony\Component\DomCrawler\Crawler;

class TokenMailTest extends FeatureTestCase
{

    /**
     * @test
     */
    public function it_sends_a_link_with_the_token ()
    {

        $user = new User([
            'first_name' => 'Dawin',
            'last_name' => 'Valenzuela',
            'email' => 'dawin.valenzuela@gmail.com'
        ]);

        $token = new Token([
            'token' => 'this-is-a-token',
            'user' => $user
        ]);


        $this->open(new \App\Mail\TokenMail($token))
            ->seeLink($token->url, $token->url);


    }

    // InterctsWithMailable
    protected function open(\Illuminate\Mail\Mailable $mailable)
    {

        $transport = Mail::getSwiftMailer()->getTransport();

        $transport->flush();

        Mail::send($mailable);

        $message = $transport->messages()->first();

        $this->crawler = new Crawler($message->getBody());

        return $this;
    }

}

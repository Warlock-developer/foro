<?php

use App\Token;
use Illuminate\Support\Facades\Mail;

class RequestTokenTest extends FeatureTestCase
{

    public function test_a_guest_user_can_request_a_tocken()
    {
        // Having
        Mail::fake();

        $user = $this->defaultUser(['email' => 'dawin.valenzuela@gmail.com']);

        //when
        $this->visitRoute('token')
            ->type('dawin.valenzuela@gmail.com', 'email')
            ->press('Solicitar token');

        //then a new token is created in the database
        $token = Token::where('user_id', $user->id)->first();

        $this->assertNotNull($token, 'A token was not created');

        //and sent to the user
        Mail::assertSent(\App\Mail\TokenMail::class, function ($mail) use ($token, $user) {
            return $mail->hasTo($user) && $mail->token->id === $token->id;
        });

        $this->dontSeeIsAuthenticated();

        $this->see('Enviamos a tu email un enlace para que inicies sesión');

    }


    public function test_a_guest_user_can_request_a_tocken_without_an_email()
    {
        // Having
        Mail::fake();

        //when
        $this->visitRoute('token')
            ->press('Solicitar token');

        //then a new token is Not created in the database
        $token = Token::first();

        $this->assertNull($token, 'A token was not created');

        //and sent to the user
        Mail::assertNotSent(\App\Mail\TokenMail::class);

        $this->dontSeeIsAuthenticated();

        $this->seeErrors([
            'email' => 'El campo correo electrónico es obligatorio'
        ]);



    }


    public function test_a_guest_user_can_request_a_tocken_an_invalid_email()
    {
        //when
        $this->visitRoute('token')
            ->type('Prueba','email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'Correo electrónico no es un correo válido'
        ]);



    }


    public function test_a_guest_user_can_request_a_tocken_with_a_non_existent_email()
    {
        $this->defaultUser(['email' => 'dawin.valenzuela@gmail.com']);

        //when
        $this->visitRoute('token')
            ->type('dawin777@hotmail.com','email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'Este correo electrónico no existe'
        ]);



    }

}

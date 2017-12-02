<?php

use App\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthenticationTest extends FeatureTestCase
{

    public function test_a_user_can_login_with_a_token_url()
    {
        //Having
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        //When
        //$this->visit(route('login', $token));
        $this->visit("login/{$token->token}");


        //Then
        $this->seeIsAuthenticated()
            ->seeIsAuthenticatedAs($user);

        $this->dontSeeInDatabase('tokens',[
            'id' => $token->id
        ]);

        $this->seePageIs('/');
    }


    public function test_a_user_cannot_login_with_an_invalid_token()
    {
        //havin
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $invalidToken = str_random(60);

        //when
        $this->visit("login/{$invalidToken}");


        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicite otro');

        $this->seeInDatabase('tokens', [
            'id' => $token->id
        ]);

    }


    public function test_a_user_cannot_use_the_same_token_twice()
    {
        //havin
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $token->login();//inicia sesión

        Auth::logout();//fin sesión

        //when
        $this->visit("login/{$token->token}");


        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicite otro');

    }


    public function test_the_token_expires_after_30_minutes()
    {
        //havin
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        Carbon::setTestNow(Carbon::parse('+31 minutes'));

        //when
        $this->visitRoute('login', ['token' => $token->token]);


        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicite otro');

    }


    function test_the_token_is_case_sensitive()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        //when
        $this->visitRoute('login', ['token' => strtolower($token->token)]);


        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicite otro');

    }

}

<?php


use App\{User, Token};
use App\Mail\TokenMail;
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends FeatureTestCase
{
    public function test_a_user_can_create_an_account()
    {
        Mail::fake();

        $this->visitRoute('register')
            ->type('dawin.valenzuela@gmail.com','email')
            ->type('admin','username')
            ->type('Dawin','first_name')
            ->type('Valenzuela','last_name')
            ->press('Regístrate');

        $this->seeInDatabase('users',[
            'email' => 'dawin.valenzuela@gmail.com',
            'username' => 'admin',
            'first_name' => 'Dawin',
            'last_name' => 'Valenzuela',
        ]);

        $user = User::first();

        $this->seeInDatabase('tokens',[
            'user_id' => $user->id
        ]);

        $token = Token::where('user_id', $user->id)->first();

        $this->assertNotNull($token);//comprobamos que si se creo un token

        Mail::assertSent(TokenMail::class, function($mail) use($token,$user){
            return $mail->hasTo($user) && $mail->token->id == $token->id;
        });
    }
}

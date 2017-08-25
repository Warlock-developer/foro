<?php


class ExampleTest extends FeatureTestCase
{

    /**
     * A basic functional test example.
     *
     * @return void
     */
    function test_basic_example()
    {
        $name = 'Dawin Valenzuela';

        $user = factory(\App\User::class)->create([
            'name' => $name,
            'email' => 'dawin.valenzuela@gmail.com'
        ]);

        $this->actingAs($user,'api')
            ->visit('api/user')
             ->see($name)
             ->see('dawin.valenzuela@gmail.com');
    }
}

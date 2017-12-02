<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'first_name' => 'Dawin',
            'last_name' => 'Valenzuela',
            'username' => 'admin',
            'email' => 'dawin.valenzuela@gmail.com',
            'role' => 'admin',
        ]);
    }
}

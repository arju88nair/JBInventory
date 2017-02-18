<?php

class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();
        User::create(array(
            'name'     => 'Nair',
            'username' => 'Nair',
            'email'    => 'nair@nair.com',
            'password' => Hash::make('awesome'),
        ));
    }

}
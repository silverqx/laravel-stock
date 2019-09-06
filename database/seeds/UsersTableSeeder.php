<?php

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;

use App\Modules\User\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function run()
    {
        User::create([
            'email'        => 'silver.zachara@gmail.com',
            'first_name'   => 'Silver',
            'last_name'    => 'Zachara',
            'password'     => app('hash')->make('pass'),
        ]);
        User::create([
            'email'        => 'peter@example.com',
            'first_name'   => 'Peter',
            'last_name'    => 'Janovič',
            'password'     => app('hash')->make('pass'),
        ]);
    }
}

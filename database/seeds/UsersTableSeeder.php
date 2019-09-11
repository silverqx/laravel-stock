<?php

use Illuminate\Support\Arr;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;

use App\Modules\User\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Avatars.
     *
     * @var array
     */
    private $userImages;

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function run()
    {
        $this->userImages = Storage::disk('seed_images')->files('users');

        $this->attachImageTo(User::create([
            'email'        => 'silver.zachara@gmail.com',
            'first_name'   => 'Silver',
            'last_name'    => 'Zachara',
            'password'     => app('hash')->make('pass'),
        ]));
        $this->attachImageTo(User::create([
            'email'        => 'peter@example.com',
            'first_name'   => 'Peter',
            'last_name'    => 'Janovič',
            'password'     => app('hash')->make('pass'),
        ]));
        $this->attachImageTo(User::create([
            'email'        => 'andrej@example.com',
            'first_name'   => 'Andrej',
            'last_name'    => 'Hasil',
            'password'     => app('hash')->make('pass'),
        ]));
    }

    /**
     * Add avatar to a User.
     *
     * @param User $user
     *
     * @return User
     */
    private function attachImageTo(User $user): User
    {
        $user
            ->addMedia(
                Storage::disk('seed_images')->path(
                    // Random
                    Arr::pull($this->userImages, array_rand($this->userImages))
                    // Sequential
//                    array_shift($this->userImages)
                )
            )
            ->preservingOriginal()
            ->toMediaCollection('users');

        return $user;
    }
}

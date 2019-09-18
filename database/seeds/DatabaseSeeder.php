<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this
             ->call(PermissionsSeeder::class)
             ->call(UsersTableSeeder::class)
             ->call(ProductsTableSeeder::class);
    }
}

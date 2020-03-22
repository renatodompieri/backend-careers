<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = App\User::forceCreate([
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'status' => 'activated',
        ]);

        $user->assignRole('admin');
    }
}
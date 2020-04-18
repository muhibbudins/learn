<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@e-learning.com',
            'password' => Hash::make('admin'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Lecturer',
            'email' => 'lecturer@e-learning.com',
            'password' => Hash::make('lecturer'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Student',
            'email' => 'student@e-learning.com',
            'password' => Hash::make('student'),
            'role' => 'student'
        ]);

        User::create([
            'name' => 'Guest',
            'email' => 'guest@e-learning.com',
            'password' => Hash::make('guest'),
            'role' => 'guest'
        ]);

        // run with php artisan migrate --seed
    }
}

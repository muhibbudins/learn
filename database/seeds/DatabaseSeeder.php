<?php

use App\User;
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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@e-learning.com',
            'password' => Hash::make('admin'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Sample Lecturer',
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

        factory(App\User::class, 300)->create();

        // run with php artisan migrate --seed
    }
}

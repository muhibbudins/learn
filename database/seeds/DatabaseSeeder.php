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
        /**
         * Create default user for admin, lecturer and student
         */
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

        /**
         * Create random 300 users with faker and role/password equal "student"
         */
        factory(App\User::class, 300)->create();

        // run with php artisan migrate --seed
    }
}

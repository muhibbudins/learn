<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $date = Carbon::create(2020, 1, 1, 0, 0, 0);
    $email = $faker->email;
    $current = $date->addDays(rand(1, 200))->format('Y-m-d H:i:s');

    return [
        'name' => strstr($email, '@', true),
        'email' => $email,
        'email_verified_at' => $current,
        'password' => Hash::make('student'),
        'role' => 'student',
        'created_at' => $current,
        'remember_token' => Str::random(10),
    ];
});

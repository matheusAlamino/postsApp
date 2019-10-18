<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Notification;
use App\User;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker) {
    return [
        'text' => $faker->text(200),
        'id_usuario' => factory(User::class)->create()->id,
        'seen' => $faker->boolean(45)
    ];
});

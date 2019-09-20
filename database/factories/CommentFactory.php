<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'description' => $faker->text(255),
        'id_post' => factory(Post::class)->create()->id,
        'id_usuario' => factory(User::class)->create()->id
    ];
});

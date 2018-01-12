<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'tel' => $faker->unique()->phoneNUmber,
        'password' => $password ?: $password = bcrypt('testpassword'),
        'emailVerificationToken' => $faker->optional($weight = 0.1, $default = 'success')->lexify('????????????????'),
        'telVerifications' => $faker->optional($weight = 0.1, $default = -1)->numberBetween(0, 15),
        'remember_token' => str_random(10),
    ];
});

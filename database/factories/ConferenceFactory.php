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

$factory->define(App\Conference::class, function (Faker $faker) {
    $conf_name = strtoupper($faker->unique()->lexify("??"));
    return [
        'name' => $conf_name . "MUN 201X",
        'shortname' => $conf_name."MUN",
        'fullname' => "201X年".$conf_name." 模拟联合国大会",
        'description' => $faker->text(200),
    ];
});

$factory->state(App\Conference::class, 'reg', function ($faker) {
    $st_date = $faker->dateTimeBetween('+1 months', '+3 months');
    $ed_date = clone $st_date;
    $ed_date->add(new DateInterval('P3D'));
    return [
        'status' => 'reg',
        'date_start' => $st_date->format('Y-m-d'),
        'date_end' => $faker->dateTimeInInterval($ed_date, '+ 3 days')->format('Y-m-d'),
    ];
});

//TOOD: more states to come

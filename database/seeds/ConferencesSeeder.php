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

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TODO: create conferences of different status
        factory(App\Conference::class, 10)->states('reg')->create()->each(function ($c) {
            DB::table('domains')->insert([
                'conference_id' => $c->id,
                'domain' => strtolower($c->shortname).config('munpanel.mainDomain')
            ]);
        });
    }
}

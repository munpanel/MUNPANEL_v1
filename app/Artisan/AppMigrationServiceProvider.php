<?php
/**
 * Copyright (C) Console iT
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

namespace App\Artisan;

use Illuminate\Support\ServiceProvider;

class AppMigrationServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton('migration.creator', function ($app) {
            return new AppMigrationCreator($app['files']);
        });
    }

    public function provides()
    {
        return ['migration.creator'];
    }
}

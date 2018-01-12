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

return array(
    'dsn' => env('SENTRY_DSN'),
    'dsn_public' => env('SENTRY_DSN_PUBLIC'),

    // capture release as git sha
     'release' => trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD')),

    // Capture bindings on SQL queries
    'breadcrumbs.sql_bindings' => true,

    // Capture default user context
    'user_context' => true,
);

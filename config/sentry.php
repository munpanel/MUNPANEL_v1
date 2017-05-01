<?php

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

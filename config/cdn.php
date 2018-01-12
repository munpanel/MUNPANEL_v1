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

return [
    'qiniu_ak' => env('QINIU_AK'),
    'qiniu_sk' => env('QINIU_SK'),
    'qiniu_bucket' => env('QINIU_BUCKET'),
    'qiniu_domain' => env('QINIU_DOMAIN', 'dn-chaoli.qbox.me'),
    'rackspace_username' => env('RACKSPACE_USERNAME'),
    'rackspace_key' => env('RACKSPACE_KEY'),
    'rackspace_sid' => env('RACKSPACE_SID'),
    'rackspace_domain' => env('RACKSPACE_DOMAIN', 'adamyi.scdn3.secure.raxcdn.com'),
    'prefix' => env('CDN_PREFIX', 'munpanel'),
    'enabled' => env('CDN_ENABLED', false),
];

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
    'domain' => env('RECAPTCHA_DOMAIN', 'www.google.com'),
    'sitekey' => env('RECAPTCHA_SITEKEY'),
    'secretkey' => env('RECAPTCHA_SECRETKEY')
];

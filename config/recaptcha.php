<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

return [
    'domain' => env('RECAPTCHA_DOMAIN', 'www.google.com'),
    'sitekey' => env('RECAPTCHA_SITEKEY'),
    'secretkey' => env('RECAPTCHA_SECRETKEY')
];

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

/**
 * Return a new Eloquent Collection class
 */
function eloquent_collect($items = []) {
    return new Illuminate\Database\Eloquent\Collection($items);
}


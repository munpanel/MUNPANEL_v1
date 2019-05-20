<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

/**
 * Return a new Eloquent Collection class
 */
function eloquent_collect($items = []) {
    return new Illuminate\Database\Eloquent\Collection($items);
}


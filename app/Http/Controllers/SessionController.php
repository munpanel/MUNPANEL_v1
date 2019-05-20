<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App\Http\Controllers;

class SessionController extends Controller
{
    /**
     * Keep the session from timing out.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function keepalive()
    {
        return response('', 204);
    }
}

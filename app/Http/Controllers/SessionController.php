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

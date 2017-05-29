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

namespace App\Http\Middleware;

use Closure;
use Config;

class CheckreCAPTCHA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = app('request')->input('g-recaptcha-response');
        if (is_null($response))
            return redirect()->back();
        $parameters = http_build_query([
            'secret'   => value(config('recaptcha.secretkey')),
            'remoteip' => app('request')->getClientIp(),
            'response' => $response,
        ]);
        $url           = 'https://'.config('recaptcha.domain').'/recaptcha/api/siteverify?' . $parameters;
        $checkResponse = null;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, app('config')->get('recaptcha.options.curl_timeout', 1));
        $checkResponse = curl_exec($curl);
        if(false === $checkResponse) {
          app('log')->error('[reCAPTCHA] CURL error: '.curl_error($curl));
        }
        if (is_null($checkResponse) || empty( $checkResponse )) {
            return redirect()->back();
        }
        $decodedResponse = json_decode($checkResponse, true);
        if ($decodedResponse['success'])
            return $next($request);
        return redirect()->back();
    }
}

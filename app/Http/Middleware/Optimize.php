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

class Optimize
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
    $response = $next($request);
    if ($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
        return $response;
    } else {
        $buffer = $response->getContent();
        if (strpos($buffer, '<pre>') !== false) {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/" => '<?php ',
                "/\r/" => '',
                "/>\n</" => '><',
                "/>\s+\n</" => '><',
                "/>\n\s+</" => '><',
            );
        } else {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/" => '<?php ',
                "/\n([\S])/" => '$1',
                "/\r/" => '',
                "/\n+/" => "\n",
                "/\t/" => '',
                "/ +/" => ' ',
            );
        }
        // Don't remove \n, as in JS
        // adamyi is cute //yassi is also cute
        // 1+1=?
        // will turn to
        // adamyi is cute //yassi is also cute 1+1=?
        // so the 1+1=? won't be executed
        $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);
        $response->setContent($buffer);
        //ini_set('zlib.output_compression', 'On');
        // Works not so well with nginx, disable zlib
        return $response;
    }
  }
}

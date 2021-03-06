<?php
/**
 * Brain Train - Find the job you love!
 * Copyright (c) Brain Train Kenya. All Rights Reserved
 *
 * Website: http://www.braintrainke.com
 *
 * CODED WITH LOVE
 * ---------------
 * 	@author : Wanekeya Sam
 *  Title   : Full-stack Developer
 * 	created	: 01 September, 2017
 *	version : 1.0
 * 	website : https://www.wanekeyasam.co.ke
 *	Email   : contact@wanekeyasam.co.ke
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Auth;

class XSSProtection
{
    /**
     * The following method loops through all request input and strips out all tags from
     * the request. This to ensure that users are unable to set ANY HTML within the form
     * submissions, but also cleans up input.
     *
	 * @param HttpRequest $request
	 * @param Closure $next
	 * @return mixed
	 */
    public function handle(HttpRequest $request, Closure $next)
    {
        if (Request::segment(1) == config('larapen.admin.route_prefix', 'admin')) {
            if (Auth::check() and Auth::user()->is_admin == 1) {
                return $next($request);
            }
        }
        
        /*
        // Only POST, PUT & PATCH methods. Comment this condition to match all methods.
        if (!in_array(strtolower($request->method()), ['post', 'put', 'patch'])) {
            return $next($request);
        }
        */
        
        $input = $request->all();
        
        array_walk_recursive($input, function (&$input, $key) use ($request) {
            if (!in_array($key, ['description'])) {
                $input = strip_tags($input);
            }
        });
        
        $request->merge($input);
        
        return $next($request);
    }
}

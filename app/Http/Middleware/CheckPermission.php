<?php

namespace App\Http\Middleware;
use Session;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$parent)
    {
        $collection = collect(Session::get('user_permission'));
        $CheckPermission = $collection->where('mp_name', $parent);
        if(count($CheckPermission) == 0)
            return back()->with("message","You do NOT have ".$parent." permission");
        else
            return $next($request);
    }
}

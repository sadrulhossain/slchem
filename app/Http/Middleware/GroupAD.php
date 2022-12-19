<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class GroupAD {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!in_array(Auth::user()->group_id, [1, 4])) {
            return redirect('dashboard');
        }
        return $next($request);
    }

}

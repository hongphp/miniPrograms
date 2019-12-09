<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 13:14
 */
namespace App\Http\Middleware;
use Closure;

class AdminTokenMiddleware
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
        $check = auth('admin')->check();
        if($check === false) {
            return response()->json(['error'=>'token expire!'],401);
        }

        return $next($request);
    }
}
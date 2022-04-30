<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;

//class VerifyHeaderAccept
class VerifyHeadersForApi
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $accept
     * @param string $contentType
     * @return mixed
     */
    public function handle(
        Request $request,
        Closure $next,
        string $accept,
        string $contentType
    ): mixed
    {
        if($request->accepts($accept)){
            if(
                in_array($request->method(), ['POST', 'PUT', 'PATCH']) and
                $request->header('Content-Type') !== $contentType)
            {
                return redirect(RouteServiceProvider::HOME);
            } else {
                return $next($request);
            }
        }
        return redirect(RouteServiceProvider::HOME);
    }
}

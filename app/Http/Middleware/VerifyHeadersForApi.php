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
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $accept
     * @return mixed
     */
    public function handle(
        Request $request,
        Closure $next,
        string $accept
    ): mixed
    {
        if($request->accepts($accept)) {
//            if(
//                in_array($request->method(), ['POST', 'PUT', 'PATCH']) and
//                !in_array($request->header('Content-Type'), ['application/json', 'multipart/form-data']))
////                $request->header('Content-Type') !== $contentTypes)
//            {
////                return redirect(RouteServiceProvider::HOME);
//                return abort(400, $request->header('Content-Type') === 'multipart/form-data' ? 'Правда': 'Ложь');
//            } else {
//                return $next($request);
//            }
            return $next($request);
        }
        return redirect(RouteServiceProvider::HOME);
    }
}

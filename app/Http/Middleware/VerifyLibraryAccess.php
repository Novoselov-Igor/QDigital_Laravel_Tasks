<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyLibraryAccess
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()
            && ($request->route('authorId') === '' . Auth::user()->id || isset(
                    Auth::user()->library()
                        ->where('user_id', Auth::user()->id)
                        ->where('author_id', $request->route('author_id'))
                        ->first()->pivot)
            )) {
            return $next($request);
        }
        return back()->with('accessError', 'У вас нет доступа к этой библиотеке');
    }
}

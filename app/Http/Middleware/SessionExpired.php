<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class SessionExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sedang login
        // if (Auth::check()) {
        //     // Cek apakah session masih valid
        //     if (!$request->session()->isValidId($request->session()->getId())) {
        //         Auth::logout();
        //         $request->session()->flush();
        //         Alert::error('Oops...', 'Session telah berakhir. Silakan login kembali.');
        //         return redirect()->route('login');
        //     }
        // }

        return $next($request);
    }
}
